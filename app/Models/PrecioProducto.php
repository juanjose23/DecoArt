<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrecioProducto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'detalleproducto_id',
        'precio',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'detalle_producto_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'detalleproducto_id' => 'integer',
        'precio' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'detalle_producto_id' => 'integer',
    ];

    public function detalleProducto(): BelongsTo
    {
        return $this->belongsTo(DetalleProducto::class);
    }

   
}
