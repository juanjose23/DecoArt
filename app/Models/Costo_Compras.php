<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costo_Compras extends Model
{
    use HasFactory;
    protected $table ="costo_compras";
    protected $fillable = [
        'compra_id',
        'costo_envio',
        'costo_aduana',
        'iva',
        'subtotal',
        'total',
    ];
    public function compra()
    {
        return $this->belongsTo(Compras::class);
    }
    
}
