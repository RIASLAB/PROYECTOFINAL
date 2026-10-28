<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Mascota;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Citas de hoy por atender
        $citasHoy = Cita::whereDate('fecha', today())->whereIn('estado', ['pendiente', 'confirmada'])->count();
        $clientesActivos = User::whereIn('role', ['user', 'cliente'])->where('status', 'activo')->count();
        $mascotasRegistradas = Mascota::count();

        return view('reception.dashboard', compact('citasHoy', 'clientesActivos', 'mascotasRegistradas'));
    }
}
