<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (!Schema::hasColumn('citas', 'vet_id')) {
                $table->unsignedBigInteger('vet_id')->nullable()->after('mascota_id');
                // La FK la añadimos en un paso separado para evitar problemas de motor / colación
            }
        });

        // Añadir la FK en un paso aparte (evita errores silenciosos)
        Schema::table('citas', function (Blueprint $table) {
            if (Schema::hasColumn('citas', 'vet_id')) {
                // Evita duplicar claves si ya existe
                try {
                    $table->foreign('vet_id')->references('id')->on('users')->nullOnDelete();
                } catch (\Throwable $e) {
                    // si ya existía, seguimos sin romper
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (Schema::hasColumn('citas', 'vet_id')) {
                try { $table->dropForeign(['vet_id']); } catch (\Throwable $e) {}
                $table->dropColumn('vet_id');
            }
        });
    }
};

