<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Factura;
use App\Models\Mascota;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $usuariosRegistrados = User::count();
        $clientesActivos     = User::whereIn('role', ['user', 'cliente'])->where('status', 'activo')->count();
        $citasProgramadas    = Cita::whereIn('estado', ['pendiente', 'confirmada'])->whereDate('fecha', '>=', today())->count();
        $mascotas            = Mascota::count();
        $veterinarios        = User::where('role', 'veterinario')->count();
        $facturas            = Factura::count();

        // Valor cobrado en el mes actual (HU19)
        $pagadasMes          = Factura::where('estado', 'pagada')->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()]);
        $cobradoMes          = (float) (clone $pagadasMes)->sum('total');
        $facturasPagadasMes  = (clone $pagadasMes)->count();

        return view('admin.dashboard', compact(
            'usuariosRegistrados', 'clientesActivos', 'citasProgramadas',
            'mascotas', 'veterinarios', 'facturas', 'cobradoMes', 'facturasPagadasMes'
        ));
    }
}
