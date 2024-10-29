<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajustes_inventario extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaccion_id', 
        'motivo', 
        'cantidad_ajuste'
    ];

    // Relación con el modelo Transaccion
    public function transaccion()
    {
        return $this->belongsTo(Transacciones::class, 'transaccion_id');
    }
}
