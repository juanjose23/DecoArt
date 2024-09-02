<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'caducidad',
        'subcategoria_id',
        'estado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'caducidad' => 'boolean',
        'subcategoria_id' => 'integer',
    ];

    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(Subcategorias::class);
    }

    public function detalleProductos(): HasMany
    {
        return $this->hasMany(DetalleProducto::class);
    }
}
