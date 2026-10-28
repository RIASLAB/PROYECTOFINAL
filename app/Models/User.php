<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'role',    // si existe
    'status',  // si existe
];
// <-- importante

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /** El rol "user" corresponde al cliente (dueño de mascota) */
    public function esCliente(): bool
    {
        return in_array($this->role, ['user', 'cliente'], true);
    }

    /** Personal de la clínica: administrador, recepcionista o veterinario */
    public function esPersonal(): bool
    {
        return in_array($this->role, ['admin', 'recepcionista', 'veterinario'], true);
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'dueno');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


}
