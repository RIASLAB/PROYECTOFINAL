<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crea el primer usuario administrador para poder entrar al sistema después de instalarlo.
 * El correo y la contraseña se pueden definir en el .env (ADMIN_EMAIL y ADMIN_PASSWORD);
 * la contraseña debe cambiarse en "Perfil" después del primer ingreso.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@lugovet.com')],
            [
                'name'     => 'Administrador Lugo Vet',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'LugoVet2026*')),
                'role'     => 'admin',
                'status'   => 'activo',
            ]
        );
    }
}
