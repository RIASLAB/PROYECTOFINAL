<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','role')) {
                $table->string('role')->default('user'); // admin|veterinario|recepcionista|user(cliente)
            }
            if (!Schema::hasColumn('users','status')) {
                $table->string('status')->default('activo'); // activo|inactivo
            }
            if (!Schema::hasColumn('users','assigned_vet_id')) {
                $table->foreignId('assigned_vet_id')->nullable()
                      ->constrained('users')->nullOnDelete();
            }
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','assigned_vet_id')) $table->dropConstrainedForeignId('assigned_vet_id');
            if (Schema::hasColumn('users','status')) $table->dropColumn('status');
            if (Schema::hasColumn('users','role')) $table->dropColumn('role');
        });
    }
};