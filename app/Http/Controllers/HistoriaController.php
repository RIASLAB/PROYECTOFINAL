<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Historia;
use App\Models\Mascota;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoriaController extends Controller
{
    private const REGLAS = [
        'motivo'          => ['required', 'string', 'max:255'],
        'anamnesis'       => ['nullable', 'string'],
        'diagnostico'     => ['nullable', 'string'],
        'tratamiento'     => ['nullable', 'string'],
        'recomendaciones' => ['nullable', 'string'],
    ];

    /** Solo el veterinario asignado a la cita puede registrar o editar su historia. */
    private function exigirVetAsignado(?Cita $cita): void
    {
        abort_unless(
            $cita && Auth::user()->role === 'veterinario' && (int) $cita->vet_id === (int) Auth::id(),
            403
        );
    }

    /** ¿Puede el usuario ver esta historia? */
    private function puedeVer(Historia $historia): bool
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'recepcionista', 'veterinario'], true)) {
            // El veterinario puede consultar cualquier historia para conocer los antecedentes de la mascota (HU16)
            return true;
        }

        $mascota = optional($historia->cita)->mascota;

        return $user->esCliente() && $mascota && $mascota->perteneceA($user);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['cita_id' => ['required', 'integer', 'exists:citas,id']] + self::REGLAS);
        $cita = Cita::findOrFail($data['cita_id']);
        $this->exigirVetAsignado($cita);

        Historia::create(['cita_id' => $cita->id, 'vet_id' => Auth::id()] + collect($data)->except('cita_id')->all());

        return back()->with('ok', 'Historia guardada.');
    }

    public function update(Request $request, Historia $historia)
    {
        $data = $request->validate(self::REGLAS);
        $this->exigirVetAsignado($historia->cita);
        $historia->update($data);

        return back()->with('ok', 'Historia actualizada.');
    }

    /** Crea o actualiza desde el modal de la agenda. */
    public function save(Request $request)
    {
        if ($request->filled('historia_id')) {
            $data = $request->validate(['historia_id' => ['required', 'integer', 'exists:historias,id']] + self::REGLAS);
            $historia = Historia::with('cita')->findOrFail($data['historia_id']);
            $this->exigirVetAsignado($historia->cita);
            $historia->update(collect($data)->except('historia_id')->all());

            return back()->with('ok', 'Historia actualizada.');
        }

        return $this->store($request);
    }

    /** Filtros comunes de los listados (fechas y búsqueda agrupada). */
    private function filtrar(Builder $query, Request $request): Builder
    {
        $q     = trim((string) $request->q);
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        return $query
            ->when($desde, fn ($qq) => $qq->whereHas('cita', fn ($c) => $c->whereDate('fecha', '>=', $desde)))
            ->when($hasta, fn ($qq) => $qq->whereHas('cita', fn ($c) => $c->whereDate('fecha', '<=', $hasta)))
            ->when($q !== '', function ($qq) use ($q) {
                // Agrupado para no saltarse el filtro principal (vet o dueño)
                $qq->where(function ($w) use ($q) {
                    $w->where('motivo', 'like', "%{$q}%")
                      ->orWhereHas('cita.mascota', fn ($m) => $m->where('nombre', 'like', "%{$q}%"));
                });
            });
    }

    private function listado(Builder $query, Request $request, string $scope, string $titulo, array $extra = [])
    {
        $hist = $this->filtrar($query->with(['cita.mascota', 'cita.vet']), $request)
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('historias.mine', [
            'scope'  => $scope,
            'titulo' => $titulo,
            'hist'   => $hist,
            'q'      => trim((string) $request->q),
            'desde'  => $request->input('desde'),
            'hasta'  => $request->input('hasta'),
        ] + $extra);
    }

    /** Historias registradas por el veterinario autenticado. */
    public function mineVet(Request $request)
    {
        return $this->listado(Historia::where('vet_id', Auth::id()), $request, 'vet', 'Mis historias clínicas');
    }

    /** Historial completo de una mascota, incluidas las historias de otros veterinarios (HU16). */
    public function deMascota(Request $request, Mascota $mascota)
    {
        $query = Historia::whereHas('cita', fn ($c) => $c->where('mascota_id', $mascota->id));

        return $this->listado($query, $request, 'mascota', 'Historial clínico de '.$mascota->nombre, ['mascota' => $mascota]);
    }

    /** Historias de las mascotas del cliente autenticado (HU05). */
    public function mineClient(Request $request)
    {
        $user  = Auth::user();
        $query = Historia::whereHas('cita.mascota', fn ($m) => $m->deUsuario($user));

        return $this->listado($query, $request, 'client', 'Historias de mis mascotas');
    }

    public function show(Historia $historia)
    {
        $historia->load(['cita.mascota', 'cita.vet', 'recetas.items']);
        abort_unless($this->puedeVer($historia), 403);

        return view('historias.show', compact('historia'));
    }

    /** Historia clínica en PDF, con sus recetas (HU12). */
    public function pdf(Historia $historia)
    {
        $historia->load(['cita.mascota', 'cita.vet', 'recetas.items']);
        abort_unless($this->puedeVer($historia), 403);

        return Pdf::loadView('historias.pdf', [
            'historia' => $historia,
            'generado' => now()->format('d/m/Y H:i'),
        ])->setPaper('letter', 'portrait')->download('historia_'.$historia->id.'.pdf');
    }

    /** El veterinario envía la historia a caja para que la recepción la cobre. */
    public function enviarACaja(Historia $historia)
    {
        if (Auth::user()->role === 'veterinario') {
            $this->exigirVetAsignado($historia->cita);
        }

        $historia->pendiente_cobro = true;
        $historia->enviado_caja_at = now();
        $historia->save();

        return back()->with('ok', 'Historia enviada a caja. Ahora la recepción puede cobrarla.');
    }

    public function retirarDeCaja(Historia $historia)
    {
        if (Auth::user()->role === 'veterinario') {
            $this->exigirVetAsignado($historia->cita);
        }

        $historia->pendiente_cobro = false;
        $historia->enviado_caja_at = null;
        $historia->save();

        return back()->with('ok', 'Historia retirada de caja.');
    }
}
