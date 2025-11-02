<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historia_id')->constrained('historias')->cascadeOnDelete();
            $table->foreignId('vet_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mascota_id')->constrained('mascotas')->cascadeOnDelete();
            $table->date('fecha');
            $table->text('indicaciones')->nullable();   // indicaciones generales
            $table->text('notas')->nullable();          // notas para el cliente
            $table->timestamps();
        });

        Schema::create('receta_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->string('medicamento');
            $table->string('dosis')->nullable();        // 10mg, 1 tableta, etc.
            $table->string('frecuencia')->nullable();   // cada 8h, 2 veces/día
            $table->string('duracion')->nullable();     // por 5 días
            $table->string('via')->nullable();          // oral, IM, tópica
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('receta_items');
        Schema::dropIfExists('recetas');
    }
};
