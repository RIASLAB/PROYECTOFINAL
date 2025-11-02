<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // Ajusta el orden de after() según tengas tus columnas
            if (!Schema::hasColumn('citas', 'estado')) {
                $table->string('estado')->default('pendiente')->after('motivo');
            }
            if (!Schema::hasColumn('citas', 'notas')) {
                $table->text('notas')->nullable()->after('estado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (Schema::hasColumn('citas', 'notas')) {
                $table->dropColumn('notas');
            }
            if (Schema::hasColumn('citas', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }
};
