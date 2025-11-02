<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historias', function (Blueprint $table) {
            // Si tu tabla tuviera id_cita, la renombramos a cita_id
            if (Schema::hasColumn('historias', 'id_cita') && !Schema::hasColumn('historias', 'cita_id')) {
                $table->renameColumn('id_cita', 'cita_id');
            }

            // Si NO existe cita_id, la creamos
            if (!Schema::hasColumn('historias', 'cita_id')) {
                $table->unsignedBigInteger('cita_id')->index()->after('id');
                // Si quieres FK (opcional, si ya existe la tabla citas):
                // $table->foreign('cita_id')->references('id')->on('citas')->cascadeOnDelete();
            }

            // vet_id (opcional, por si tampoco existe)
            if (!Schema::hasColumn('historias', 'vet_id')) {
                $table->unsignedBigInteger('vet_id')->nullable()->index()->after('cita_id');
                // $table->foreign('vet_id')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('historias', function (Blueprint $table) {
            if (Schema::hasColumn('historias', 'cita_id')) $table->dropColumn('cita_id');
            if (Schema::hasColumn('historias', 'vet_id'))  $table->dropColumn('vet_id');
        });
    }
};
