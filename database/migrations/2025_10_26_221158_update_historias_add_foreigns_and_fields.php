<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historias', function (Blueprint $table) {
            if (!Schema::hasColumn('historias', 'cita_id')) {
                $table->foreignId('cita_id')
                      ->constrained('citas')      // FK a citas.id
                      ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('historias', 'vet_id')) {
                $table->foreignId('vet_id')
                      ->constrained('users')      // FK a users.id
                      ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('historias', 'motivo')) {
                $table->string('motivo');
            }
            if (!Schema::hasColumn('historias', 'anamnesis')) {
                $table->text('anamnesis')->nullable();
            }
            if (!Schema::hasColumn('historias', 'diagnostico')) {
                $table->text('diagnostico')->nullable();
            }
            if (!Schema::hasColumn('historias', 'tratamiento')) {
                $table->text('tratamiento')->nullable();
            }
            if (!Schema::hasColumn('historias', 'recomendaciones')) {
                $table->text('recomendaciones')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('historias', function (Blueprint $table) {
            if (Schema::hasColumn('historias', 'cita_id')) {
                $table->dropForeign(['cita_id']);
                $table->dropColumn('cita_id');
            }
            if (Schema::hasColumn('historias', 'vet_id')) {
                $table->dropForeign(['vet_id']);
                $table->dropColumn('vet_id');
            }
            if (Schema::hasColumn('historias', 'motivo'))          $table->dropColumn('motivo');
            if (Schema::hasColumn('historias', 'anamnesis'))       $table->dropColumn('anamnesis');
            if (Schema::hasColumn('historias', 'diagnostico'))     $table->dropColumn('diagnostico');
            if (Schema::hasColumn('historias', 'tratamiento'))     $table->dropColumn('tratamiento');
            if (Schema::hasColumn('historias', 'recomendaciones')) $table->dropColumn('recomendaciones');
        });
    }
};
