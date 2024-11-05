<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Recepciones extends Model
{
    use HasFactory;
    protected $table ="detalle_recepciones";
    protected $fillable = [
        'recepciones_id', 
        'detalleproducto_id', 
        'fecha_vencimiento', 
        'cantidad_recibida', 
        'cantidad_esperada'
    ];
    public function recepciones()
    {
        return $this->belongsTo(Recepciones::class);
    }
    public function detalleProductos()
    {
        return $this->belongsTo(DetalleProducto::class,'detalleproducto_id');
    }
    
}

