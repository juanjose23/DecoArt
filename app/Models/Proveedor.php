<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'sector_comercial',
        'razon_social',
        'ruc',
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
    public function compras()
    {
        return $this->Hasmany(Compras::class);
    }
    public function contactoProveedors(): HasMany
    {
        return $this->hasMany(ContactoProveedor::class);
    }
}
