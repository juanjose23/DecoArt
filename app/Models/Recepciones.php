<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recepciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'compras_id',
        'fecha_recepcion',
        'estado'
    ];
    public function compras()
    {
        return $this->belongsTo(Compras::class,'compras_id');
    }
    public function detalleRecepciones()
    {
        return $this->hasMany(Detalle_Recepciones::class, 'recepciones_id');
    }
}
