<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteDetalle extends Model
{
    use HasFactory;
    protected $table = 'lote_detalles';
    protected $fillable = [
        'lote_id',
        'detalleproducto_id',
        'cantidad_inicial',
        'cantidad_disponible',
    ];
    // Relación con el modelo DetalleProducto
    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class, 'detalleproducto_id');
    }
    public function lote()
    {
        return $this->belongsTo(Lotes::class);
    }

    public function inventarios()
    {
        return $this->hasMany(Inventarios::class);
    }
    
}
