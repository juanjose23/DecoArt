<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Compras extends Model
{
    protected $table="detalle_compras";
    use HasFactory;
    protected $fillable = [
        'compras_id',
        'detalleproducto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'iva_unitario',
    ];
    public function detalleProductos()
    {
        return $this->belongsTo(DetalleProducto::class,'detalleproducto_id');
    }
    public function compras()
    {
        return $this->belongsTo(Compras::class);
    }
}
