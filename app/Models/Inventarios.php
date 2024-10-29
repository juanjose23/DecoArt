<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventarios extends Model
{
    use HasFactory;
    protected $fillable = [
        'detalleproducto_id',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'stock_disponible',
    ];
    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class, 'detalleproducto_id');
    }
}
