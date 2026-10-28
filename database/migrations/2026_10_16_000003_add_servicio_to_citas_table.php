<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tipo de servicio de la cita: distingue las citas clínicas de las de estética (objetivo específico 3).
    public function up(): void
    {
        if (!Schema::hasColumn('citas', 'servicio')) {
            Schema::table('citas', function (Blueprint $table) {
                $table->string('servicio', 30)->default('consulta')->after('mascota_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('citas', 'servicio')) {
            Schema::table('citas', function (Blueprint $table) {
                $table->dropColumn('servicio');
            });
        }
    }
};
