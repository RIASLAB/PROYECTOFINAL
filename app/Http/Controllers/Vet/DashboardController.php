<?php
// app/Http/Controllers/Vet/DashboardController.php
namespace App\Http\Controllers\Vet;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $u = auth()->user();

        // Tabla de citas (nombre “citas” en tu app)
        $table = 'citas';

        // Columnas probables (usamos lo que exista para no romper)
        $colVet     = Schema::hasColumn($table, 'veterinario_id') ? 'veterinario_id' : null;
        $colEstado  = Schema::hasColumn($table, 'estado') ? 'estado' : null;
        $colCliente = Schema::hasColumn($table, 'user_id') ? 'user_id'
                    : (Schema::hasColumn($table, 'cliente_id') ? 'cliente_id' : null);
        $colMascota = Schema::hasColumn($table, 'mascota_id') ? 'mascota_id' : null;

        // Filtro base por veterinario (si no hay columna, el conteo será global)
        $base = DB::table($table);
        if ($colVet) $base->where($colVet, $u->id);

        // 1) Citas Programadas
        $q1 = clone $base;
        if ($colEstado) $q1->where($colEstado, 'programada');
        $citasProgramadas = (int) $q1->count();

        // 2) Clientes atendidos (DISTINCT por cliente si hay columna)
        $clientesAtendidos = 0;
        if ($colCliente) {
            $q2 = clone $base;
            $clientesAtendidos = (int) $q2->distinct()->count($colCliente);
        }

        // 3) Mascotas atendidas (DISTINCT por mascota si hay columna)
        $mascotasAtendidas = 0;
        if ($colMascota) {
            $q3 = clone $base;
            $mascotasAtendidas = (int) $q3->distinct()->count($colMascota);
        }

        return view('vet.dashboard', compact(
            'citasProgramadas',
            'clientesAtendidos',
            'mascotasAtendidas'
        ));
    }
}
