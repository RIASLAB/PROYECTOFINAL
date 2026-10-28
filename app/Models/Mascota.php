<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascotas';

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'edad',
        'dueno',
    ];


    public function citas()
{
    return $this->hasMany(\App\Models\Cita::class);
}


 // Si 'dueno' guarda el ID del usuario, esto resolverá el nombre.
    /**
     * Mascotas de un usuario. El campo "dueno" guarda el id del usuario; los registros antiguos
     * pueden tener el nombre o el correo, por eso se aceptan los tres valores.
     */
    public function scopeDeUsuario($query, User $user)
    {
        $vals = array_map(fn ($v) => mb_strtolower(trim((string) $v)), [$user->id, $user->name, $user->email]);

        return $query->where(function ($w) use ($vals) {
            foreach ($vals as $v) {
                $w->orWhereRaw('LOWER(TRIM(dueno)) = ?', [$v]);
            }
        });
    }

    public function perteneceA(User $user): bool
    {
        return static::query()->whereKey($this->getKey())->deUsuario($user)->exists();
    }

    public function owner()
    {
        // foreignKey = 'dueno' (columna que tienes hoy)
        // owner key = 'id' en users
        return $this->belongsTo(User::class, 'dueno', 'id');
    }
}




