<?php
// database/migrations/2025_10_29_000001_add_pendiente_cobro_to_historias_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('historias', function (Blueprint $table) {
            if (!Schema::hasColumn('historias', 'pendiente_cobro')) {
                $table->boolean('pendiente_cobro')->default(false)->after('recomendaciones');
            }
        });
    }

    public function down(): void {
        Schema::table('historias', function (Blueprint $table) {
            if (Schema::hasColumn('historias', 'pendiente_cobro')) {
                $table->dropColumn('pendiente_cobro');
            }
        });
    }
};
