<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('facturas', function (Blueprint $t) {
      $t->id();
      $t->foreignId('historia_id')->constrained()->cascadeOnDelete();
      $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // quien la creó
      $t->string('cliente')->nullable();
      $t->string('mascota')->nullable();
      $t->decimal('subtotal', 12, 2)->default(0);
      $t->decimal('impuesto', 6, 2)->default(0); // porcentaje
      $t->decimal('total', 12, 2)->default(0);
      $t->enum('estado', ['borrador','pendiente','pagada'])->default('pendiente');
      $t->timestamp('paid_at')->nullable();
      $t->timestamps();
      $t->index(['historia_id','estado']);
    });
  }
  public function down(): void { Schema::dropIfExists('facturas'); }
};