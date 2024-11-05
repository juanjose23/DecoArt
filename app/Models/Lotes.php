<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo', 
        
        'es_caducable', 
        'fecha_expiracion',
        'estado',
    ];
    public function loteDetalles()
    {
        return $this->hasMany(LoteDetalle::class,'lote_id');
    }

    

    // Relación con el modelo Transaccion (un lote puede tener muchas transacciones)
    public function transacciones()
    {
        return $this->hasMany(Transacciones::class, 'lote_id');
    }
}
