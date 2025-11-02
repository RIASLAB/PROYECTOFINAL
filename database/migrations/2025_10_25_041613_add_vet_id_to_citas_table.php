<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // nullable para no romper registros existentes
            $table->unsignedBigInteger('vet_id')->nullable()->after('mascota_id');

            // índice + clave foránea (set null si borran el usuario)
            $table->index('vet_id');
            $table->foreign('vet_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['vet_id']);
            $table->dropIndex(['vet_id']);
            $table->dropColumn('vet_id');
        });
    }
};
