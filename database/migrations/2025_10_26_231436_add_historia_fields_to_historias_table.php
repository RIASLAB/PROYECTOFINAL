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

            $table->string('motivo', 255)->after('vet_id');
            $table->text('anamnesis')->nullable()->after('motivo');
            $table->text('diagnostico')->nullable()->after('anamnesis');
            $table->text('tratamiento')->nullable()->after('diagnostico');
            $table->text('recomendaciones')->nullable()->after('tratamiento');
        });
    }

    public function down(): void
    {
        Schema::table('historias', function (Blueprint $table) {
            $table->dropColumn(['motivo','anamnesis','diagnostico','tratamiento','recomendaciones']);
        });
    }
};
