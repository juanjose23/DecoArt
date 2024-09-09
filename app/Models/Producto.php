<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Producto extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
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
    public function generarCodigoDeBarras()
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($this->codigo, $generator::TYPE_CODE_128);

        // Devuelve el código de barras como una imagen en línea
        return '<img src="data:image/png;base64,' . base64_encode($barcode) . '" alt="Código de Barras">';
    }

    
}
