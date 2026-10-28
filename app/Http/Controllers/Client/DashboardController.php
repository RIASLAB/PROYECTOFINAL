<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Mascota;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Resumen del cliente: sus mascotas y sus próximas citas (HU06)
        $totalMascotas = Mascota::deUsuario($user)->count();
        $proximasCitas = Cita::with(['mascota', 'vet'])
            ->whereHas('mascota', fn ($m) => $m->deUsuario($user))
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->whereDate('fecha', '>=', today())
            ->orderBy('fecha')->orderBy('hora')
            ->limit(5)
            ->get();

        return view('client.dashboard', compact('totalMascotas', 'proximasCitas'));
    }
}
