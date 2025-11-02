<?php

namespace App\Policies;

use App\Models\Mascota;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MascotaPolicy
{
    public function view(User $user, Mascota $mascota): bool
{
    return in_array($user->role, ['admin','recepcionista','veterinario'])
        || $mascota->owner_id === $user->id;
}

public function update(User $user, Mascota $mascota): bool
{
    if (in_array($user->role, ['admin','recepcionista'])) return true;
    if ($user->role === 'user') return $mascota->owner_id === $user->id;
    if ($user->role === 'veterinario') return true; // edita ficha clínica
    return false;
}
}