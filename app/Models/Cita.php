<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /** Estados válidos de una cita */
    public const ESTADOS = ['pendiente', 'confirmada', 'cancelada', 'completada'];

    /** Tipos de servicio que presta la veterinaria (entrevista al propietario) */
    public const SERVICIOS = [
        'consulta'    => 'Consulta general',
        'control'     => 'Control',
        'vacunacion'  => 'Vacunación',
        'ecografia'   => 'Ecografía',
        'radiografia' => 'Radiografía',
        'cirugia'     => 'Cirugía',
    ];

    /** Procedimientos que agenda la recepción después de la consulta */
    public const PROCEDIMIENTOS = ['ecografia', 'radiografia', 'cirugia'];

    protected $fillable = [
        'mascota_id', 'servicio', 'fecha', 'hora', 'motivo', 'estado', 'observaciones', 'vet_id',
    ];

    protected $attributes = [
        'estado'   => 'pendiente',
        'servicio' => 'consulta',
    ];

    public function mascota()
    {
        return $this->belongsTo(\App\Models\Mascota::class);
    }

    public function vet()
    {
        return $this->belongsTo(\App\Models\User::class, 'vet_id');
    }

    public function historia()
    {
        return $this->hasOne(\App\Models\Historia::class);
    }

    public function getServicioNombreAttribute(): string
    {
        return self::SERVICIOS[$this->servicio ?? 'consulta'] ?? ucfirst((string) $this->servicio);
    }

    public function getEsProcedimientoAttribute(): bool
    {
        return in_array($this->servicio, self::PROCEDIMIENTOS, true);
    }
}
