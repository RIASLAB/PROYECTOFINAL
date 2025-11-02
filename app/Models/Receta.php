<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $fillable = [
        'historia_id',
        'vet_id',
        'mascota_id',
        'fecha',
        'indicaciones',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function historia()  { return $this->belongsTo(Historia::class); }
    public function vet()       { return $this->belongsTo(User::class, 'vet_id'); }
    public function mascota()   { return $this->belongsTo(Mascota::class, 'mascota_id'); }
}