<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CitaPolicy
{
    use HandlesAuthorization;

    // Si tienes admins, descomenta:
    // public function before(User $user, $ability)
    // {
    //     if ($user->is_admin ?? false) return true;
    // }

    /** Chequea si el usuario es dueño de la cita (dueno puede ser id, nombre o email) */
    private function owns(User $user, Cita $cita): bool
    {
        $dueno = optional($cita->mascota)->dueno;
        if ($dueno === null) return false;

        // Igualar por ID (numérico/string)
        if ((string)$dueno === (string)$user->id) return true;

        // Normalizar strings (trim + lowercase)
        $duenoStr = trim(mb_strtolower((string)$dueno));
        $userName = trim(mb_strtolower((string)$user->name));
        $userMail = trim(mb_strtolower((string)$user->email));

        return ($duenoStr === $userName) || ($duenoStr === $userMail);
    }

    public function viewAny(User $user): bool { return true; }

    public function view(User $user, Cita $cita): bool
    {
        return $this->owns($user, $cita);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Cita $cita): bool
    {
        return $this->owns($user, $cita);
    }

    public function delete(User $user, Cita $cita): bool
    {
        return $this->owns($user, $cita);
    }
}
