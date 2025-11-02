<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $usuariosRegistrados = class_exists(\App\Models\User::class) ? \App\Models\User::count() : 0;
        $clientesActivos     = class_exists(\App\Models\User::class) ? \App\Models\User::where('role','user')->count() : 0;
        $citasProgramadas    = class_exists(\App\Models\Cita::class) ? \App\Models\Cita::count() : 0;

        // extras, por si luego los usas
        $mascotas            = class_exists(\App\Models\Mascota::class) ? \App\Models\Mascota::count() : 0;
        $servicios           = class_exists(\App\Models\Servicio::class) ? \App\Models\Servicio::count() : 0;
        $veterinarios        = class_exists(\App\Models\User::class) ? \App\Models\User::where('role','veterinario')->count() : 0;
        $facturas            = class_exists(\App\Models\Factura::class) ? \App\Models\Factura::count() : 0;

        return view('admin.dashboard', compact(
            'usuariosRegistrados','clientesActivos','citasProgramadas',
            'mascotas','servicios','veterinarios','facturas'
        ));
    }
}
