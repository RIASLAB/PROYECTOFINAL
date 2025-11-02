<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('factura_items', function (Blueprint $t) {
      $t->id();
      $t->foreignId('factura_id')->constrained('facturas')->cascadeOnDelete();
      $t->string('descripcion');
      $t->integer('cantidad')->default(1);
      $t->decimal('precio', 12, 2)->default(0);
      $t->decimal('subtotal', 12, 2)->default(0);
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('factura_items'); }
};