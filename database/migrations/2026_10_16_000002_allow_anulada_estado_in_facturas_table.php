<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // El enum original no incluía "anulada", por lo que anular una factura fallaba en MySQL.
    // Se cambia a texto y los valores válidos se controlan en el código (Factura::ESTADOS).
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('estado', 20)->default('pendiente')->change();
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->enum('estado', ['borrador', 'pendiente', 'pagada'])->default('pendiente')->change();
        });
    }
};
