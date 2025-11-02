<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CitaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Devuelve el nombre de la columna de propietario en mascotas (compatibilidad)
     */
    private function ownerColumn(): ?string
    {
        return Schema::hasColumn('mascotas', 'dueno') ? 'dueno' : null;
    }

    /**
     * Aplica filtro de dueño a un query de Mascotas.
     * Tolera valores en dueno como ID, nombre o email, y espacios/mayúsculas.
     */
    private function applyOwnerFilterToMascotas(Builder $query): Builder
    {
        $col = $this->ownerColumn();
        if (!$col) {
            // Si no existe la columna, no devuelvas nada
            return $query->whereRaw('1=0');
        }

        $u  = Auth::user();
        $id = strtolower(trim((string) $u->id));
        $nm = strtolower(trim((string) $u->name));
        $em = strtolower(trim((string) $u->email));

        return $query->where(function ($w) use ($col, $id, $nm, $em) {
            $w->whereRaw("LOWER(TRIM($col)) = ?", [$id])
              ->orWhereRaw("LOWER(TRIM($col)) = ?", [$nm])
              ->orWhereRaw("LOWER(TRIM($col)) = ?", [$em]);
        });
    }

    /**
     * Aplica filtro de dueño a un query de Citas vía relación mascota
     */
    private function scopeOnlyMy(Builder $query): Builder
    {
        $col = $this->ownerColumn();
        if (!$col) {
            return $query->whereRaw('1=0');
        }

        return $query->whereHas('mascota', function ($q) {
            $this->applyOwnerFilterToMascotas($q);
        });
    }

    /**
     * Listado de citas (solo las del usuario actual) con búsqueda agrupada
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->q);

        $citas = Cita::with('mascota')
            ->tap(fn ($qry) => $this->scopeOnlyMy($qry))
            ->when($q !== '', function ($query) use ($q) {
                // Agrupamos los OR para que no rompan el filtro OnlyMy
                $query->where(function ($s) use ($q) {
                    $s->where('motivo', 'like', "%{$q}%")
                      ->orWhere('observaciones', 'like', "%{$q}%")
                      ->orWhereHas('mascota', fn ($mq) => $mq->where('nombre', 'like', "%{$q}%"));
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('citas.index', compact('citas', 'q'));
    }

    /**
     * Formulario crear
     */
    public function create()
    {
        $this->authorize('create', Cita::class);

        // Solo mascotas del usuario actual
        $mascotas = Mascota::query();
        $this->applyOwnerFilterToMascotas($mascotas);
        $mascotas = $mascotas->orderBy('nombre')->get(['id', 'nombre']);

        return view('citas.create', compact('mascotas'));
    }

    /**
     * Guardar nueva cita
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cita::class);

        // Normaliza hora (acepta “1:30 pm”, “13:30”, etc.) a H:i
        if ($request->filled('hora')) {
            $hora = $this->normalizeHora($request->input('hora'));
            $request->merge(['hora' => $hora]); // puede quedar null si no es válida
        }

        $validated = $request->validate([
            'mascota_id'    => ['required', 'integer', 'exists:mascotas,id'],
            'fecha'         => ['required', 'date'],
            'hora'          => ['required', 'date_format:H:i'],
            'motivo'        => ['required', 'string', 'max:255'],
            'estado'        => ['nullable', Rule::in(['pendiente', 'confirmada', 'cancelada', 'completada'])],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            // si vas a asignar vet desde el formulario, descomenta:
            // 'vet_id'        => ['nullable','integer','exists:users,id'],
        ]);
        $validated['estado'] = $validated['estado'] ?? 'pendiente';

        // Verifica que la mascota sea del usuario actual
        $esMia = Mascota::query()
            ->where('id', $validated['mascota_id'])
            ->tap(fn ($q) => $this->applyOwnerFilterToMascotas($q))
            ->exists();

        if (!$esMia) {
            return back()->withInput()
                ->withErrors(['mascota_id' => 'No puedes usar una mascota que no es tuya.']);
        }

        // ====== Validación de solapamiento ======
        $this->assertNoOverlap(
            fecha: $validated['fecha'],
            hora:  $validated['hora'],
            mascotaId: $validated['mascota_id'],
            vetId: $validated['vet_id'] ?? null,
            ignoreCitaId: null // en store no ignoramos nada
        );
        // ========================================

        Cita::create($validated);

        return redirect()->route('citas.index')->with('ok', 'Cita creada.');
    }

    /**
     * Ver detalle
     */
    public function show(Cita $cita)
    {
        $cita->load('mascota');
        $this->authorize('view', $cita);

        return view('citas.show', compact('cita'));
    }

    /**
     * Formulario editar
     */
    public function edit(Cita $cita)
    {
        $cita->load('mascota');            // Necesario para la policy
        $this->authorize('update', $cita);

        // Solo mascotas del usuario actual
        $mascotas = Mascota::query();
        $this->applyOwnerFilterToMascotas($mascotas);
        $mascotas = $mascotas->orderBy('nombre')->get(['id', 'nombre']);

        return view('citas.edit', compact('cita', 'mascotas'));
    }

    /**
     * Actualizar cita
     */
    public function update(Request $request, Cita $cita)
    {
        $cita->load('mascota');
        $this->authorize('update', $cita);

        if ($request->filled('hora')) {
            $hora = $this->normalizeHora($request->input('hora'));
            $request->merge(['hora' => $hora]); // puede quedar null si no es válida
        }

        $validated = $request->validate([
            'mascota_id'    => ['required', 'integer', 'exists:mascotas,id'],
            'fecha'         => ['required', 'date'],
            'hora'          => ['required', 'date_format:H:i'],
            'motivo'        => ['required', 'string', 'max:255'],
            'estado'        => ['nullable', Rule::in(['pendiente', 'confirmada', 'cancelada', 'completada'])],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            // 'vet_id'        => ['nullable','integer','exists:users,id'],
        ]);
        $validated['estado'] = $validated['estado'] ?? 'pendiente';

        // Verifica que la mascota seleccionada sea del usuario
        $esMia = Mascota::query()
            ->where('id', $validated['mascota_id'])
            ->tap(fn ($q) => $this->applyOwnerFilterToMascotas($q))
            ->exists();

        if (!$esMia) {
            return back()->withInput()
                ->withErrors(['mascota_id' => 'No puedes asignar una mascota que no es tuya.']);
        }

        // ====== Validación de solapamiento ======
        $this->assertNoOverlap(
            fecha: $validated['fecha'],
            hora:  $validated['hora'],
            mascotaId: $validated['mascota_id'],
            vetId: $validated['vet_id'] ?? null,
            ignoreCitaId: $cita->id // en update ignoramos la propia
        );
        // ========================================

        $cita->update($validated);

        return redirect()->route('citas.index')->with('ok', 'Cita actualizada.');
    }

    /**
     * Eliminar cita
     */
    public function destroy(Request $request, Cita $cita)
    {
        $cita->load('mascota');
        $this->authorize('delete', $cita);

        $cita->delete();

        return back()->with('ok', 'Cita eliminada correctamente.');
    }

    /**
     * Normaliza hora a formato 24h H:i (acepta "1:49 pm", "01:49 p. m.", "13:49", etc.)
     */
    private function normalizeHora(?string $valor): ?string
    {
        if (!$valor) return null;

        $raw = trim(mb_strtolower($valor));

        // variantes comunes en español
        $raw = str_replace(
            ['a. m.', 'p. m.', 'a.m.', 'p.m.', 'a. m', 'p. m', 'a m', 'p m', ' am', ' pm', 'a.m', 'p.m'],
            ['am',    'pm',    'am',   'pm',   'am',   'pm',  'am',  'pm',  'am',  'pm',  'am',  'pm'],
            $raw
        );
        $raw = preg_replace('/\s+/', ' ', $raw); // colapsar espacios

        // Si ya viene en 24h (H:i), la respetamos
        if (preg_match('/^\d{1,2}:\d{2}$/', $raw)) {
            return $raw;
        }

        // Si viene en 12h con am/pm
        if (preg_match('/^\d{1,2}:\d{2}\s?(am|pm)$/', $raw)) {
            try {
                return Carbon::createFromFormat('h:i a', $raw)->format('H:i');
            } catch (\Throwable $e) {
                return null; // hará fallar la validación
            }
        }

        // No reconocido
        return null;
    }

    /**
     * Verifica que no exista solapamiento para la misma mascota (y opcionalmente para el vet)
     * En este ejemplo asumimos duración fija de 30 minutos. Si sólo guardas HH:MM exacto,
     * el chequeo por igualdad de hora es suficiente; te dejo ambos enfoques.
     */
    private function assertNoOverlap(string $fecha, string $hora, int $mascotaId, ?int $vetId = null, ?int $ignoreCitaId = null): void
    {
        // === Caso simple: si tu sistema usa SLOT exacto por minuto (HH:MM exacto) ===
        $existeMismaHora = Cita::where('mascota_id', $mascotaId)
            ->whereDate('fecha', $fecha)
            ->where('hora', $hora)
            ->when($ignoreCitaId, fn($q) => $q->where('id', '<>', $ignoreCitaId))
            ->exists();

        if ($existeMismaHora) {
            throw ValidationException::withMessages([
                'hora' => 'Ya existe una cita para esta mascota en ese horario.',
            ]);
        }

        // (Opcional) evitar solapamiento por veterinario si lo usas
        if ($vetId) {
            $ocupadoVet = Cita::where('vet_id', $vetId)
                ->whereDate('fecha', $fecha)
                ->where('hora', $hora)
                ->when($ignoreCitaId, fn($q) => $q->where('id', '<>', $ignoreCitaId))
                ->exists();

            if ($ocupadoVet) {
                throw ValidationException::withMessages([
                    'hora' => 'El veterinario ya tiene una cita en ese horario.',
                ]);
            }
        }

        /* === Enfoque de rango (si manejas duración real) — Descomenta si lo usas ===
        $durMin = 30; // duración estándar
        $ini = Carbon::createFromFormat('H:i', $hora);
        $fin = (clone $ini)->addMinutes($durMin);

        $solapaMascota = Cita::where('mascota_id', $mascotaId)
            ->whereDate('fecha', $fecha)
            ->when(true, function ($q) use ($ini, $fin) {
                // Si tuvieras columnas start_time/end_time:
                // $q->where(function($w) use ($ini, $fin) {
                //     $w->where('start_time', '<', $fin->format('H:i'))
                //       ->where('end_time', '>', $ini->format('H:i'));
                // });
                // Con una sola "hora", usa igualdad como fallback
                $q->where('hora', $ini->format('H:i'));
            })
            ->when($ignoreCitaId, fn($q) => $q->where('id', '<>', $ignoreCitaId))
            ->exists();

        if ($solapaMascota) {
            throw ValidationException::withMessages([
                'hora' => 'Ya existe una cita para esta mascota que se solapa con ese horario.',
            ]);
        }

        if ($vetId) {
            $solapaVet = Cita::where('vet_id', $vetId)
                ->whereDate('fecha', $fecha)
                ->when(true, function ($q) use ($ini, $fin) {
                    // Igual que arriba: si tienes rangos, aplica intersección; si no, igualdad.
                    $q->where('hora', $ini->format('H:i'));
                })
                ->when($ignoreCitaId, fn($q) => $q->where('id', '<>', $ignoreCitaId))
                ->exists();

            if ($solapaVet) {
                throw ValidationException::withMessages([
                    'hora' => 'El veterinario ya tiene una cita que se solapa con ese horario.',
                ]);
            }
        }
        */
    }

    public function pdf(Request $request)
    {
        $desde = $request->date('desde');
        $hasta = $request->date('hasta');
        $q     = trim((string)$request->q);

        $citas = Cita::with('mascota')
            ->tap(fn ($qry) => $this->scopeOnlyMy($qry))
            ->when($desde, fn($qq) => $qq->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn($qq) => $qq->whereDate('fecha', '<=', $hasta))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($s) use ($q) {
                    $s->where('motivo', 'like', "%{$q}%")
                      ->orWhere('observaciones', 'like', "%{$q}%")
                      ->orWhereHas('mascota', fn($mq) => $mq->where('nombre', 'like', "%{$q}%"));
                });
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $user = $request->user();

        $pdf = Pdf::loadView('citas.pdf', [
            'citas' => $citas,
            'user'  => $user,
            'desde' => $desde,
            'hasta' => $hasta,
            'q'     => $q,
            'generado' => now()->format('d/m/Y H:i')
        ])->setPaper('letter', 'portrait');

        $nombre = 'citas_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($nombre);
    }

public function completar(\Illuminate\Http\Request $request, \App\Models\Cita $cita)
{
    
    // Sólo el vet asignado puede completar
    abort_unless(
        auth()->check() &&
        auth()->user()->role === 'veterinario' &&
        (int)$cita->vet_id === (int)auth()->id(),
        403
    );

    $cita->update(['estado' => 'completada']);

    return back()->with('ok','Cita marcada como completada.');
}



}
