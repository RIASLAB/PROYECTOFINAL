<?php

namespace App\Http\Controllers;

use App\Models\Historia;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RecetaController extends Controller
{
    // GET /vet/historias/{historia}/recetas
    public function index(Historia $historia)
    {
        // seguridad mínima: solo el vet asignado puede ver/crear
        abort_unless(
            auth()->check() &&
            auth()->user()->role === 'veterinario' &&
            (int) optional($historia->cita)->vet_id === (int) auth()->id(),
            403
        );

        $recetas = $historia->recetas()->latest()->paginate(12);

        return view('recetas.index', compact('historia', 'recetas'));
    }

    // GET /vet/historias/{historia}/recetas/create
    public function create(Historia $historia)
    {
        abort_unless(
            auth()->check() &&
            auth()->user()->role === 'veterinario' &&
            (int) optional($historia->cita)->vet_id === (int) auth()->id(),
            403
        );

        return view('recetas.create', compact('historia'));
    }

    // POST /vet/historias/{historia}/recetas
   // app/Http/Controllers/RecetaController.php

public function store(Request $request, \App\Models\Historia $historia)
{
    // Solo el veterinario asignado a la cita puede emitir recetas de esta historia
    abort_unless((int) optional($historia->cita)->vet_id === (int) auth()->id(), 403);

    $mascotaId = optional($historia->cita)->mascota_id;

    $data = $request->validate([
        'indicaciones'          => ['required','string'],
        'notas'                 => ['nullable','string'],
        'fecha'                 => ['nullable','date'],
        'items'                 => ['required','array'],
        'items.*.medicamento'   => ['nullable','string','max:255'],
        'items.*.dosis'         => ['nullable','string','max:255'],
        'items.*.frecuencia'    => ['nullable','string','max:255'],
        'items.*.duracion'      => ['nullable','string','max:255'],
        'items.*.via'           => ['nullable','string','max:255'],
    ]);

    // Medicamentos diligenciados (HU15)
    $items = collect($data['items'])->filter(fn ($it) => filled($it['medicamento'] ?? null))->values();
    if ($items->isEmpty()) {
        return back()->withInput()->withErrors(['items' => 'Registra al menos un medicamento con su dosis.']);
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($historia, $mascotaId, $data, $items) {
        $receta = \App\Models\Receta::create([
            'historia_id'  => $historia->id,
            'vet_id'       => auth()->id(),
            'mascota_id'   => $mascotaId,
            'fecha'        => $data['fecha'] ?? now(),
            'indicaciones' => $data['indicaciones'],
            'notas'        => $data['notas'] ?? null,
        ]);

        foreach ($items as $it) {
            $receta->items()->create(collect($it)->only(['medicamento','dosis','frecuencia','duracion','via'])->all());
        }
    });

    return redirect()
        ->route('vet.recetas.index', $historia)
        ->with('ok', 'Receta creada correctamente.');
}

    // GET /vet/recetas/{receta}
    public function show(Receta $receta)
    {
        $receta->load('historia.cita.mascota', 'historia.cita.vet', 'items');

        abort_unless(
            auth()->check() &&
            auth()->user()->role === 'veterinario' &&
            (int) optional($receta->historia->cita)->vet_id === (int) auth()->id(),
            403
        );

        return view('recetas.show', compact('receta'));
    }

    // Opcional: tu listado personal /vet/recetas
     public function mine(Request $request)
    {
        $q   = trim($request->q ?? '');
        $uid = Auth::id();

        $recetas = Receta::with(['mascota','historia'])
            ->where('vet_id', $uid) // <- la clave del fix
            ->when($q, function ($query) use ($q) {
                $query->where(function($q2) use ($q) {
                    $q2->where('indicaciones','like',"%{$q}%")
                       ->orWhere('notas','like',"%{$q}%");
                })
                ->orWhereHas('mascota', fn($m) => $m->where('nombre','like',"%{$q}%"));
            })
            ->orderByDesc('fecha')
            ->paginate(12)
            ->withQueryString();

        return view('recetas.mine', compact('recetas','q'));
    }
}
