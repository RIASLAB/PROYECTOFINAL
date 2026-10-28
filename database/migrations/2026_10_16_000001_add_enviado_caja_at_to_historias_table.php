<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // La columna existía en la base de datos de producción pero no en ninguna migración.
    public function up(): void
    {
        if (!Schema::hasColumn('historias', 'enviado_caja_at')) {
            Schema::table('historias', function (Blueprint $table) {
                $table->timestamp('enviado_caja_at')->nullable()->after('pendiente_cobro');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('historias', 'enviado_caja_at')) {
            Schema::table('historias', function (Blueprint $table) {
                $table->dropColumn('enviado_caja_at');
            });
        }
    }
};
