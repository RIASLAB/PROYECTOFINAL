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
    public function owner()
    {
        // foreignKey = 'dueno' (columna que tienes hoy)
        // owner key = 'id' en users
        return $this->belongsTo(User::class, 'dueno', 'id');
    }
}




