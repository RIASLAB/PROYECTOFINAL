<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Historia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class HistoriaController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'cita_id'         => ['required','integer','exists:citas,id'],
            'motivo'          => ['required','string','max:255'],
            'anamnesis'       => ['nullable','string'],
            'diagnostico'     => ['nullable','string'],
            'tratamiento'     => ['nullable','string'],
            'recomendaciones' => ['nullable','string'],
        ]);

        // seguridad: solo el vet asignado a la cita
        $cita = Cita::findOrFail($data['cita_id']);
        abort_unless(
            auth()->check() &&
            auth()->user()->role === 'veterinario' &&
            (int)$cita->vet_id === (int)auth()->id(),
            403
        );

        // crea (si tu esquema exige 1 historia por cita, agrega índice único a cita_id)
        Historia::create([
            'cita_id'         => $cita->id,
            'vet_id'          => auth()->id(),
            'motivo'          => $data['motivo'],
            'anamnesis'       => $data['anamnesis'] ?? null,
            'diagnostico'     => $data['diagnostico'] ?? null,
            'tratamiento'     => $data['tratamiento'] ?? null,
            'recomendaciones' => $data['recomendaciones'] ?? null,
        ]);

        return back()->with('ok','Historia guardada.');
    }

    
   public function update(Request $request, Historia $historia)
{

    $data = $request->validate([
        'motivo'          => ['required','string','max:255'],
        'anamnesis'       => ['nullable','string'],
        'diagnostico'     => ['nullable','string'],
        'tratamiento'     => ['nullable','string'],
        'recomendaciones' => ['nullable','string'],
    ]);

    $cita = $historia->cita; // relación historia->cita

    // Seguridad: sólo el vet asignado puede editar
    abort_unless(
        auth()->user()->role === 'veterinario' &&
        (int) $cita->vet_id === (int) auth()->id(),
        403
    );

    $historia->update($data);

    return back()->with('ok', 'Historia actualizada.');
}

public function save(Request $request)
{
    if ($request->filled('historia_id')) {
        // ===== UPDATE =====
        $data = $request->validate([
    'historia_id'     => ['required','integer','exists:historias,id'],
    'motivo'          => ['required','string','max:255'],
    'anamnesis'       => ['nullable','string'],
    'diagnostico'     => ['nullable','string'],
    'tratamiento'     => ['nullable','string'],
    'recomendaciones' => ['nullable','string'],
]);

$historia = \App\Models\Historia::with('cita')->findOrFail($data['historia_id']);
$cita     = $historia->cita;

abort_unless(
    auth()->check()
    && auth()->user()->role === 'veterinario'
    && (int)$cita->vet_id === (int)auth()->id(),
    403
);

// 🔒 Asignación explícita de cada campo
$historia->motivo          = $data['motivo'];
$historia->anamnesis       = $data['anamnesis'] ?? null;
$historia->diagnostico     = $data['diagnostico'] ?? null;
$historia->tratamiento     = $data['tratamiento'] ?? null;
$historia->recomendaciones = $data['recomendaciones'] ?? null;
$historia->save();

return back()->with('ok','Historia actualizada.');

    } else {
        // ===== CREATE =====
        $data = $request->validate([
    'cita_id'         => ['required','integer','exists:citas,id'],
    'motivo'          => ['required','string','max:255'],
    'anamnesis'       => ['nullable','string'],
    'diagnostico'     => ['nullable','string'],
    'tratamiento'     => ['nullable','string'],
    'recomendaciones' => ['nullable','string'],
]);

$cita = \App\Models\Cita::findOrFail($data['cita_id']);

abort_unless(
    auth()->check()
    && auth()->user()->role === 'veterinario'
    && (int)$cita->vet_id === (int)auth()->id(),
    403
);

// 🔒 Asignación explícita (evita problemas de $fillable o nombres)
$historia = new \App\Models\Historia();
$historia->cita_id         = $cita->id;
$historia->vet_id          = auth()->id();
$historia->motivo          = $data['motivo'];
$historia->anamnesis       = $data['anamnesis'] ?? null;
$historia->diagnostico     = $data['diagnostico'] ?? null;
$historia->tratamiento     = $data['tratamiento'] ?? null;
$historia->recomendaciones = $data['recomendaciones'] ?? null;
$historia->save();

return back()->with('ok','Historia guardada.');
    }
 
}

 public function mineVet(Request $request)
    {
        $user = auth()->user();

        $q     = trim((string) $request->q);
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $hist = Historia::with(['cita.mascota','cita.vet'])
            ->where('vet_id', $user->id)
            ->when($desde, fn($qq) =>
                $qq->whereHas('cita', fn($q2) => $q2->whereDate('fecha','>=',$desde))
            )
            ->when($hasta, fn($qq) =>
                $qq->whereHas('cita', fn($q2) => $q2->whereDate('fecha','<=',$hasta))
            )
            ->when($q, function($qq) use ($q) {
                $qq->where('motivo','like',"%{$q}%")
                   ->orWhereHas('cita.mascota', fn($w) => $w->where('nombre','like',"%{$q}%"));
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('historias.mine', [
            'scope'  => 'vet',
            'titulo' => 'Mis historias clínicas',
            'hist'   => $hist,
            'q'      => $q,
            'desde'  => $desde,
            'hasta'  => $hasta,
        ]);
    }

    // 🔹 Listado de historias de las MASCOTAS del CLIENTE logueado
    public function mineClient(Request $request)
    {
        $user = auth()->user();

        $q     = trim((string) $request->q);
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        // Ajusta 'user_id' si tu columna de dueño en 'mascotas' se llama distinto
        $hist = Historia::with(['cita.mascota','cita.vet'])
            ->whereHas('cita.mascota', fn($w) => $w->where('user_id', $user->id))
            ->when($desde, fn($qq) =>
                $qq->whereHas('cita', fn($q2) => $q2->whereDate('fecha','>=',$desde))
            )
            ->when($hasta, fn($qq) =>
                $qq->whereHas('cita', fn($q2) => $q2->whereDate('fecha','<=',$hasta))
            )
            ->when($q, function($qq) use ($q) {
                $qq->where('motivo','like',"%{$q}%")
                   ->orWhereHas('cita.mascota', fn($w) => $w->where('nombre','like',"%{$q}%"));
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('historias.mine', [
            'scope'  => 'client',
            'titulo' => 'Historias de mis mascotas',
            'hist'   => $hist,
            'q'      => $q,
            'desde'  => $desde,
            'hasta'  => $hasta,
        ]);
    }

     public function show(Historia $historia)
{
    // Carga relaciones necesarias para mostrar
    $historia->load(['cita.mascota', 'cita.vet']);

    $user = auth()->user();

    // Autorización: admin, recepcionista, el vet dueño de la historia,
    // o el dueño de la mascota pueden ver.
    $puedeVer =
        ($user->role === 'admin') ||
        ($user->role === 'recepcionista') ||
        ($user->role === 'veterinario' && (int)$historia->vet_id === (int)$user->id) ||
        ($user->role === 'user' && (int)optional($historia->cita->mascota)->user_id === (int)$user->id);

    abort_unless($puedeVer, 403);

    return view('historias.show', compact('historia'));
}

 public function enviarACaja(Historia $historia)
    {
        // Permitir solo veterinario o admin
        if (!in_array(Auth::user()->role, ['veterinario', 'admin'])) {
            abort(403);
        }

        $historia->pendiente_cobro = true;
        $historia->save();

        return back()->with('ok', 'Historia enviada a caja.');
    }

    public function retirarDeCaja(Historia $historia)
    {
        if (!in_array(Auth::user()->role, ['veterinario', 'admin'])) {
            abort(403);
        }

        $historia->pendiente_cobro = false;
        $historia->save();

        return back()->with('ok', 'Historia retirada de caja.');
    }

}



