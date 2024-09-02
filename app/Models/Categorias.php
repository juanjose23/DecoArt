<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorias extends Model
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
        'estado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function subcategorias(): HasMany
    {
        return $this->hasMany(Subcategorias::class);
    }
     /**
     * Cambia el estado del registro a "eliminado" (estado = 2).
     *
     * @return void
     */
    public function markAsDeleted()
    {
        $this->update(['estado' => 0]);
    }
}
