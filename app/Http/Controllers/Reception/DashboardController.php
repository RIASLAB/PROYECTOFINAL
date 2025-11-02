<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Citas de hoy (programadas) - tolerante a esquema
        $citasHoy = 0;
        if (Schema::hasTable('citas')) {
            $q = DB::table('citas');
            $colFecha  = Schema::hasColumn('citas', 'fecha')  ? 'fecha'  : null;
            $colEstado = Schema::hasColumn('citas', 'estado') ? 'estado' : null;

            if ($colFecha)  $q->whereDate($colFecha, now()->toDateString());
            if ($colEstado) $q->where($colEstado, 'programada');

            $citasHoy = (int) $q->count();
        }

        // Clientes activos (role=user)
        $clientesActivos = 0;
        if (Schema::hasTable('users') && Schema::hasColumn('users','role')) {
            $uq = DB::table('users')->where('role','user');
            if (Schema::hasColumn('users','status')) $uq->where('status','activo');
            $clientesActivos = (int) $uq->count();
        }

        // Mascotas registradas
        $mascotasRegistradas = Schema::hasTable('mascotas')
            ? (int) DB::table('mascotas')->count()
            : 0;

        return view('reception.dashboard', compact('citasHoy','clientesActivos','mascotasRegistradas'));
    }
}
