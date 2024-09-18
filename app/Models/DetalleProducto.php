<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class DetalleProducto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'producto_id',
        'color_id',
        'marca_id',
        'material_id',
        'estado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'id' => 'integer',
        'producto_id' => 'integer',
        'color_id' => 'integer',
        'marca_id' => 'integer',
        'material_id' => 'integer',
    ];

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marcas::class, 'marca_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function precio(): HasMany
    {
        return $this->hasMany(PrecioProducto::class);
    }
}
