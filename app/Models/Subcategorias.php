<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategorias extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_id',
        'estado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'categoria_id' => 'integer',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categorias::class);
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
    public function markAsDeleted()
    {
        $this->update(['estado' => 0]);
    }
}
