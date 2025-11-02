<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
     protected $fillable = [
        'historia_id','user_id','cliente','mascota',
        'subtotal','impuesto','total','estado','paid_at'
    ];
  
    public function cliente(){ return $this->belongsTo(User::class, 'cliente_id'); }
    public function items(){ return $this->hasMany(FacturaItem::class); }
       public function historia()
    {
        return $this->belongsTo(\App\Models\Historia::class, 'historia_id');
    }

    public static function nextNumero(): string {
        $last = static::max('id') ?? 0;
        return 'FAC-'.str_pad($last + 1, 6, '0', STR_PAD_LEFT);
    }
}
