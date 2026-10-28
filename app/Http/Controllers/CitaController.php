<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CitaController extends Controller
{
    use AuthorizesRequests;

    /** Mascotas que el usuario puede usar al agendar: las propias (cliente) o todas (personal). */
    private function mascotasDisponibles(): Builder
    {
        $user = Auth::user();
        $q = Mascota::query();

        return $user->esPersonal() ? $q : $q->deUsuario($user);
    }

    /** Limita un query de citas a las del usuario si es cliente. */
    private function soloPropias(Builder $query): Builder
    {
        $user = Auth::user();

        if ($user->esCliente()) {
            $query->whereHas('mascota', fn ($m) => $m->deUsuario($user));
        } elseif ($user->role === 'veterinario') {
            $query->where('vet_id', $user->id);
        }

        return $query;
    }

    /** Listado de citas: el cliente ve solo las de sus mascotas; el veterinario, las asignadas. */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Cita::class);

        $q = trim((string) $request->q);

        $citas = $this->soloPropias(Cita::with(['mascota', 'vet']))
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($sub) use ($q) {
                    $sub->where('motivo', 'like', "%{$q}%")
                        ->orWhereHas('mascota', fn ($m) => $m->where('nombre', 'like', "%{$q}%"));
                });
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('citas.index', compact('citas', 'q'));
    }

    public function create()
    {
        $this->authorize('create', Cita::class);
        $mascotas = $this->mascotasDisponibles()->orderBy('nombre')->get(['id', 'nombre']);

        return view('citas.create', compact('mascotas'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Cita::class);
        $data = $this->validar($request);

        // Una cita nueva siempre inicia en estado pendiente; la recepción la confirma desde la agenda.
        $data['estado'] = 'pendiente';

        $this->assertNoOverlap($data['fecha'], $data['hora'], (int) $data['mascota_id'], null, null);
        Cita::create($data);

        return redirect()->route('citas.index')->with('ok', 'Cita solicitada. La recepción la confirmará y asignará el veterinario.');
    }

    public function edit(Cita $cita)
    {
        $cita->load('mascota');
        $this->authorize('update', $cita);
        $mascotas = $this->mascotasDisponibles()->orderBy('nombre')->get(['id', 'nombre']);

        return view('citas.edit', compact('cita', 'mascotas'));
    }

    public function update(Request $request, Cita $cita)
    {
        $cita->load('mascota');
        $this->authorize('update', $cita);
        $data = $this->validar($request);

        // El cliente solo puede dejar su cita pendiente o cancelarla; los demás estados los maneja la clínica.
        $estado = $request->input('estado', $cita->estado);
        if (Auth::user()->esCliente() && !in_array($estado, ['pendiente', 'cancelada'], true)) {
            $estado = $cita->estado;
        }
        $data['estado'] = in_array($estado, Cita::ESTADOS, true) ? $estado : $cita->estado;

        $this->assertNoOverlap($data['fecha'], $data['hora'], (int) $data['mascota_id'], $cita->vet_id, $cita->id);
        $cita->update($data);

        return redirect()->route('citas.index')->with('ok', 'Cita actualizada.');
    }

    public function destroy(Request $request, Cita $cita)
    {
        $cita->load('mascota');
        $this->authorize('delete', $cita);
        $cita->delete();

        return back()->with('ok', 'Cita eliminada correctamente.');
    }

    /** Validación común de creación y edición. */
    private function validar(Request $request): array
    {
        if ($request->filled('hora')) {
            $request->merge(['hora' => $this->normalizeHora($request->input('hora'))]);
        }

        $data = $request->validate([
            'mascota_id'    => ['required', 'integer', 'exists:mascotas,id'],
            'servicio'      => ['required', Rule::in(array_keys(Cita::SERVICIOS))],
            'fecha'         => ['required', 'date', 'after_or_equal:today'],
            'hora'          => ['required', 'date_format:H:i'],
            'motivo'        => ['required', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ], [
            'fecha.after_or_equal' => 'La fecha de la cita no puede ser anterior a hoy.',
        ]);

        $permitida = $this->mascotasDisponibles()->whereKey($data['mascota_id'])->exists();
        if (!$permitida) {
            throw ValidationException::withMessages(['mascota_id' => 'No puedes usar una mascota que no es tuya.']);
        }

        return $data;
    }

    /** Normaliza la hora a formato 24 h (acepta "1:30 pm", "13:30", etc.). */
    private function normalizeHora(?string $valor): ?string
    {
        if (!$valor) {
            return null;
        }

        $raw = trim(mb_strtolower($valor));
        $raw = str_replace(
            ['a. m.', 'p. m.', 'a.m.', 'p.m.', 'a. m', 'p. m', 'a m', 'p m', ' am', ' pm', 'a.m', 'p.m'],
            ['am', 'pm', 'am', 'pm', 'am', 'pm', 'am', 'pm', 'am', 'pm', 'am', 'pm'],
            $raw
        );
        $raw = preg_replace('/\s+/', ' ', $raw);

        if (preg_match('/^(\d{1,2}):(\d{2})(:\d{2})?$/', $raw, $m)) {
            return sprintf('%02d:%s', $m[1], $m[2]);
        }
        if (preg_match('/^(\d{1,2}):(\d{2})\s?(am|pm)$/', $raw, $m) && (int) $m[1] >= 1 && (int) $m[1] <= 12) {
            $h = ((int) $m[1]) % 12 + ($m[3] === 'pm' ? 12 : 0);

            return sprintf('%02d:%s', $h, $m[2]);
        }

        return null;
    }

    /** Evita dos citas de la misma mascota (o del mismo veterinario) en la misma fecha y hora. */
    private function assertNoOverlap(string $fecha, string $hora, int $mascotaId, ?int $vetId = null, ?int $ignoreCitaId = null): void
    {
        $base = fn () => Cita::whereDate('fecha', $fecha)
            ->whereIn('hora', [$hora, $hora.':00'])
            ->where('estado', '!=', 'cancelada')
            ->when($ignoreCitaId, fn ($q) => $q->where('id', '<>', $ignoreCitaId));

        if ($base()->where('mascota_id', $mascotaId)->exists()) {
            throw ValidationException::withMessages(['hora' => 'Ya existe una cita para esta mascota en ese horario.']);
        }

        if ($vetId && $base()->where('vet_id', $vetId)->exists()) {
            throw ValidationException::withMessages(['hora' => 'El veterinario ya tiene una cita en ese horario.']);
        }
    }

    public function pdf(Request $request)
    {
        $desde = $request->date('desde');
        $hasta = $request->date('hasta');
        $q     = trim((string) $request->q);

        $citas = $this->soloPropias(Cita::with('mascota'))
            ->when($desde, fn ($qq) => $qq->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn ($qq) => $qq->whereDate('fecha', '<=', $hasta))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($s) use ($q) {
                    $s->where('motivo', 'like', "%{$q}%")
                      ->orWhere('observaciones', 'like', "%{$q}%")
                      ->orWhereHas('mascota', fn ($mq) => $mq->where('nombre', 'like', "%{$q}%"));
                });
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $pdf = Pdf::loadView('citas.pdf', [
            'citas'    => $citas,
            'user'     => $request->user(),
            'desde'    => $desde,
            'hasta'    => $hasta,
            'q'        => $q,
            'generado' => now()->format('d/m/Y H:i'),
        ])->setPaper('letter', 'portrait');

        return $pdf->download('citas_'.now()->format('Ymd_His').'.pdf');
    }

    /** El veterinario asignado marca la cita como atendida. */
    public function completar(Request $request, Cita $cita)
    {
        abort_unless((int) $cita->vet_id === (int) Auth::id(), 403);
        abort_if($cita->estado === 'cancelada', 422, 'No se puede completar una cita cancelada.');

        $cita->update(['estado' => 'completada']);

        return back()->with('ok', 'Cita marcada como completada.');
    }
}
