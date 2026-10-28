<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('historias', function (Blueprint $table) {
            // Si quieres forzar FK (opcional, solo si aún no las tienes)
            // $table->unsignedBigInteger('cita_id')->change();
            // $table->unsignedBigInteger('vet_id')->change();
            // $table->foreign('cita_id')->references('id')->on('citas')->onDelete('cascade');
            // $table->foreign('vet_id')->references('id')->on('users')->onDelete('cascade');

            // Guardas: estas columnas pueden existir ya por la migración 2025_10_26_221158
            if (!Schema::hasColumn('historias', 'motivo')) {
                $table->string('motivo', 255)->after('vet_id');
            }
            if (!Schema::hasColumn('historias', 'anamnesis')) {
                $table->text('anamnesis')->nullable()->after('motivo');
            }
            if (!Schema::hasColumn('historias', 'diagnostico')) {
                $table->text('diagnostico')->nullable()->after('anamnesis');
            }
            if (!Schema::hasColumn('historias', 'tratamiento')) {
                $table->text('tratamiento')->nullable()->after('diagnostico');
            }
            if (!Schema::hasColumn('historias', 'recomendaciones')) {
                $table->text('recomendaciones')->nullable()->after('tratamiento');
            }
        });
    }

    public function down(): void
    {
        Schema::table('historias', function (Blueprint $table) {
            $table->dropColumn(['motivo','anamnesis','diagnostico','tratamiento','recomendaciones']);
        });
    }
};
