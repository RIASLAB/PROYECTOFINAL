<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaItem extends Model
{
    protected $fillable = ['factura_id','descripcion','cantidad','precio','subtotal'];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
