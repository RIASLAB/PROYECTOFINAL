<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La veterinaria no presta servicios estéticos ni de odontología (entrevista al propietario):
     * las citas que ya tenían esos tipos pasan a "consulta".
     */
    public function up(): void
    {
        if (Schema::hasColumn('citas', 'servicio')) {
            DB::table('citas')
                ->whereIn('servicio', ['bano', 'corte', 'limpieza_dental', 'odontologia'])
                ->update(['servicio' => 'consulta']);
        }
    }

    public function down(): void
    {
        // No es reversible: no se conserva el tipo anterior.
    }
};
