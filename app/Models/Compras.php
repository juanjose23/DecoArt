<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'user_id',
        'proveedor_id',
        'fecha_recepcion',
        'notas',
        'costo_envio',
        'costo_aduana',
        'iva',
        'subtotal',
        'total',
        'estado',

    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    public function detalleCompras()
    {
        return $this->HasMany(Detalle_Compras::class);
    }

    public function recepcion()
    {
        return $this->hasOne(Recepciones::class, 'compras_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
