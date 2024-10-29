<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'detalleproducto_id', 
        'lote_id', 
        'tipo', 
        'cantidad', 
        'descripcion'
    ];

    // Relación con el modelo DetalleProducto
    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class, 'detalleproducto_id');
    }

    // Relación con el modelo Lote
    public function lote()
    {
        return $this->belongsTo(Lotes::class, 'lote_id');
    }

    // Relación con el modelo AjusteInventario (una transacción puede tener un ajuste)
    public function ajusteInventario()
    {
        return $this->hasOne(Ajustes_inventario::class, 'transaccion_id');
    }
}
