<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Verifica que el usuario autenticado tenga uno de los roles permitidos.
     *
     * Uso en rutas:
     *   ->middleware('role:admin')
     *   ->middleware('role:veterinario')
     *   ->middleware('role:recepcionista')
     *   ->middleware('role:admin,recepcionista')   // varios roles separados por coma
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1) Debe estar autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 1.1) Un usuario desactivado por el administrador pierde el acceso de inmediato (HU17)
        if (($user->status ?? 'activo') === 'inactivo') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está inactiva. Comunícate con el administrador.']);
        }

        $userRole = strtolower(trim((string) ($user->role ?? '')));

        // 2) Normalizar: 'user' y 'cliente' son el mismo rol (cliente)
        $normalize = function (string $r): string {
            $r = strtolower(trim($r));
            return $r === 'user' ? 'cliente' : $r;
        };

        $userRole = $normalize($userRole);

        // 3) Lista de roles permitidos para esta ruta (también normalizados).
        //    Soporta separar por coma dentro de un mismo parámetro: role:admin,recepcionista
        $allowed = [];
        foreach ($roles as $chunk) {
            foreach (explode(',', $chunk) as $r) {
                if ($r !== '') {
                    $allowed[] = $normalize($r);
                }
            }
        }

        // 4) Si la ruta no especificó roles, basta con estar autenticado
        if (empty($allowed)) {
            return $next($request);
        }

        // 5) ¿El rol del usuario está permitido?
        if (in_array($userRole, $allowed, true)) {
            return $next($request);
        }

        // 6) Rol desconocido: no hay panel al cual enviarlo (evita un ciclo de redirecciones)
        if (!in_array($userRole, ['admin', 'veterinario', 'recepcionista', 'cliente'], true)) {
            abort(403, 'Tu usuario no tiene un rol válido.');
        }

        // 7) No autorizado: lo mandamos a SU panel correspondiente (evita pantalla 403 fea)
        return redirect()
            ->to($this->homeForRole($userRole))
            ->with('error', 'No tienes permiso para acceder a esa sección.');
    }

    /**
     * Devuelve la URL del panel principal según el rol.
     */
    private function homeForRole(string $role): string
    {
        return match ($role) {
            'admin'         => route('admin.dashboard'),
            'veterinario'   => route('vet.dashboard'),
            'recepcionista' => route('reception.dashboard'),
            default         => route('client.dashboard'), // cliente / user
        };
    }
}