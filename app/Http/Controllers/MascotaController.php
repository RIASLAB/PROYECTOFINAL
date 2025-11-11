<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


  

class MascotaController extends Controller
{

      private function ownerColumn(): ?string
    {
        // busca la columna que guarda el dueño en la tabla mascotas
        foreach (['dueno', 'user_id', 'owner_id', 'cliente_id', 'client_id', 'usuario_id'] as $col) {
            if (Schema::hasColumn('mascotas', $col)) return $col;
        }
        return null;
    }

    /**
     * Filtro: mascotas del usuario. Tolera dueno=ID, nombre o email (por datos antiguos).
     */
    private function applyOwnerFilterToMascotas($query)
    {
        $col = $this->ownerColumn();
        if (!$col) return $query->whereRaw('1=0');

        $u   = Auth::user();
        $id  = strtolower(trim((string) $u->id));
        $nm  = strtolower(trim((string) $u->name));
        $em  = strtolower(trim((string) $u->email));

        return $query->where(function ($w) use ($col, $id, $nm, $em) {
            $w->whereRaw("LOWER(TRIM($col)) = ?", [$id])
              ->orWhereRaw("LOWER(TRIM($col)) = ?", [$nm])
              ->orWhereRaw("LOWER(TRIM($col)) = ?", [$em]);
        });
    }

    
     public function index(Request $request)
{
    $user = auth()->user();
    $role = $user->role ?? null;

    $q = $request->input('q');

    $mascotas = \App\Models\Mascota::query()
        ->when($q, function ($query) use ($q) {
            $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('especie', 'like', "%{$q}%")
                ->orWhere('raza', 'like', "%{$q}%")
                ->orWhere('dueno', 'like', "%{$q}%");
        })
        ->when($role === 'user' || $role === 'cliente', function ($query) use ($user) {
            // 👇 Filtrar solo las mascotas del cliente autenticado
            $query->where(function ($q2) use ($user) {
                $q2->where('dueno', $user->id)
                   ->orWhere('dueno', $user->name);
            });
        })
        ->orderBy('id', 'asc')
        ->paginate(10);

    // Contadores
    $total = $mascotas->total();
    $perros = $mascotas->where('especie', 'perro')->count();
    $gatos = $mascotas->where('especie', 'gato')->count();

    return view('mascotas.index', compact('mascotas', 'total', 'perros', 'gatos'));
}


    public function create()
    {
        return view('mascotas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'  => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza'    => 'nullable|string|max:255',
            'edad'    => 'nullable|integer|min:0',
        ]);

        // Desde ahora guardamos SIEMPRE el ID (texto)
        $data['dueno'] = (string) Auth::id();

        Mascota::create($data);
        return redirect()->route('mascotas.index')->with('ok', 'Mascota registrada correctamente.');
    }

    public function edit(Mascota $mascota)
    {
        $this->applyOwnerFilterToMascotas($q = Mascota::query());
        abort_unless($q->where('id', $mascota->id)->exists(), 403);

        return view('mascotas.edit', compact('mascota'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        $this->applyOwnerFilterToMascotas($q = Mascota::query());
        abort_unless($q->where('id', $mascota->id)->exists(), 403);

        $data = $request->validate([
            'nombre'  => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza'    => 'nullable|string|max:255',
            'edad'    => 'nullable|integer|min:0',
        ]);

        // Mantener estandar: ID como texto
        $data['dueno'] = (string) Auth::id();

        $mascota->update($data);
        return redirect()->route('mascotas.index')->with('ok', 'Mascota actualizada correctamente.');
    }

    public function destroy(Mascota $mascota)
    {
        $this->applyOwnerFilterToMascotas($q = Mascota::query());
        abort_unless($q->where('id', $mascota->id)->exists(), 403);

        $mascota->delete();
        return back()->with('ok', 'Mascota eliminada correctamente.');
    }
}
