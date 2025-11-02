<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table = 'historias';
    public $timestamps = true;

    protected $fillable = [
        'cita_id','vet_id',
        'motivo','anamnesis','diagnostico','tratamiento','recomendaciones' ,'pendiente_cobro'
    ];
     protected $casts = [
        'pendiente_cobro' => 'boolean',
    ];

    public function cita(){ return $this->belongsTo(\App\Models\Cita::class); }
    public function vet(){  return $this->belongsTo(\App\Models\User::class,'vet_id'); }
    public function scopePendientesDeCobro($q) {
        return $q->where('pendiente_cobro', true);
    }
    public function recetas()
{
    return $this->hasMany(\App\Models\Receta::class, 'historia_id');
}

    public function facturas()
{
    return $this->hasMany(\App\Models\Factura::class, 'historia_id');
}

}
