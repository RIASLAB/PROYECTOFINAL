<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Solo actúa si la columna no existe (la migración 2025_10_25_041613 ya la crea con su clave foránea)
        if (!Schema::hasColumn('citas', 'vet_id')) {
            Schema::table('citas', function (Blueprint $table) {
                $table->unsignedBigInteger('vet_id')->nullable()->after('mascota_id');
                $table->foreign('vet_id')->references('id')->on('users')->nullOnDelete();
            });
        }
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

