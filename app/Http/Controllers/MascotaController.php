<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MascotaController extends Controller
{
    /** Recepción y administración gestionan las mascotas de todos los clientes. */
    private function esGestor(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'recepcionista'], true);
    }

    private function puedeModificar(Mascota $mascota): bool
    {
        return $this->esGestor() || $mascota->perteneceA(Auth::user());
    }

    private function clientes()
    {
        return User::whereIn('role', ['user', 'cliente'])->where('status', 'activo')->orderBy('name')->get(['id', 'name', 'email']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $q = trim((string) $request->input('q'));

        $base = Mascota::query()
            ->with('owner')
            ->when($user->esCliente(), fn ($query) => $query->deUsuario($user))
            ->when($q !== '', function ($query) use ($q) {
                // Agrupado para que la búsqueda no se salte el filtro del dueño
                $query->where(function ($w) use ($q) {
                    $w->where('nombre', 'like', "%{$q}%")
                      ->orWhere('especie', 'like', "%{$q}%")
                      ->orWhere('raza', 'like', "%{$q}%");
                });
            })
            ->orderBy('id');

        $mascotas = (clone $base)->paginate(10)->withQueryString();

        // Indicadores sobre todas las mascotas del listado, sin distinguir mayúsculas ("Perro" o "perro")
        $total  = $mascotas->total();
        $perros = (clone $base)->whereRaw('LOWER(TRIM(especie)) = ?', ['perro'])->count();
        $gatos  = (clone $base)->whereRaw('LOWER(TRIM(especie)) = ?', ['gato'])->count();

        return view('mascotas.index', compact('mascotas', 'total', 'perros', 'gatos'));
    }

    public function create()
    {
        $clientes = $this->esGestor() ? $this->clientes() : collect();

        return view('mascotas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $reglas = [
            'nombre'  => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza'    => 'nullable|string|max:255',
            'edad'    => 'nullable|integer|min:0|max:40',
        ];

        // La recepción registra la mascota a nombre de un cliente existente (HU07)
        if ($this->esGestor()) {
            $reglas['dueno_id'] = ['required', 'integer', Rule::exists('users', 'id')->whereIn('role', ['user', 'cliente'])];
        }

        $data = $request->validate($reglas, [
            'dueno_id.required' => 'Selecciona el cliente dueño de la mascota.',
            'dueno_id.exists'   => 'El cliente no está registrado. Primero debe registrarse el cliente.',
        ]);

        $data['dueno'] = (string) ($this->esGestor() ? $data['dueno_id'] : Auth::id());
        unset($data['dueno_id']);

        Mascota::create($data);

        return redirect()->route('mascotas.index')->with('ok', 'Mascota registrada correctamente.');
    }

    public function edit(Mascota $mascota)
    {
        abort_unless($this->puedeModificar($mascota), 403);

        return view('mascotas.edit', compact('mascota'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        abort_unless($this->puedeModificar($mascota), 403);

        $data = $request->validate([
            'nombre'  => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza'    => 'nullable|string|max:255',
            'edad'    => 'nullable|integer|min:0|max:40',
        ]);

        // El dueño no cambia al editar
        $mascota->update($data);

        return redirect()->route('mascotas.index')->with('ok', 'Mascota actualizada correctamente.');
    }

    public function destroy(Mascota $mascota)
    {
        abort_unless($this->puedeModificar($mascota), 403);
        $mascota->delete();

        return back()->with('ok', 'Mascota eliminada correctamente.');
    }
}
