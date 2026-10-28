<?php

namespace App\Http\Controllers\Vet;

use App\Http\Controllers\Controller;
use App\Models\Cita;

class DashboardController extends Controller
{
    public function index()
    {
        $vetId = auth()->id();
        $base  = fn () => Cita::where('vet_id', $vetId);

        // Citas por atender (pendientes o confirmadas desde hoy)
        $citasProgramadas  = $base()->whereIn('estado', ['pendiente', 'confirmada'])->whereDate('fecha', '>=', today())->count();
        // Mascotas y clientes distintos atendidos (citas completadas)
        $mascotasAtendidas = $base()->where('estado', 'completada')->distinct()->count('mascota_id');
        $clientesAtendidos = $base()->where('estado', 'completada')
            ->join('mascotas', 'mascotas.id', '=', 'citas.mascota_id')
            ->distinct()->count('mascotas.dueno');

        return view('vet.dashboard', compact('citasProgramadas', 'clientesAtendidos', 'mascotasAtendidas'));
    }
}
