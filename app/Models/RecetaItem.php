<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaItem extends Model
{
    protected $fillable = [
        'receta_id','medicamento','dosis','frecuencia','duracion','via','observaciones'
    ];

    public function receta(){ return $this->belongsTo(Receta::class); }
}
