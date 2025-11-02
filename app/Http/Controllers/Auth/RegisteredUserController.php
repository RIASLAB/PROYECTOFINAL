<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    // 1) Validar y GUARDAR en $data ✅
    $data = $request->validate([
    'name'     => ['required', 'string', 'max:255'],
    'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
    'password' => ['required', 'confirmed', Password::defaults()], // ✅
]);


    // 2) Preparar payload para creación del usuario
    $payload = [
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => Hash::make($data['password']),
    ];

    // 3) Si tu tabla users TIENE estas columnas, las seteamos
    if (Schema::hasColumn('users', 'role')) {
        $payload['role'] = 'user'; // registro público => cliente
    }
    if (Schema::hasColumn('users', 'status')) {
        $payload['status'] = 'activo';
    }

    // 4) Crear usuario
    $user = User::create($payload);

    // 5) Disparar evento y loguear
    event(new Registered($user));
    Auth::login($user);

    // 6) Redirigir según tu router por rol (ya lo tienes en /dashboard)
    return redirect()->route('dashboard');
}
}
