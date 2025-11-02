<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
    'mascota_id','fecha','hora','motivo','estado','observaciones','vet_id'
];

    protected $attributes = [
        'estado' => 'pendiente',
    ];

    public function mascota()
    {
        return $this->belongsTo(\App\Models\Mascota::class);
    }

    public function vet()
{
    return $this->belongsTo(\App\Models\User::class, 'vet_id');
}

public function historia() { return $this->hasOne(\App\Models\Historia::class); }


}
