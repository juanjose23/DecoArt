<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrecioProducto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'detalleproducto_id',
        'precio',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'detalle_producto_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'detalleproducto_id' => 'integer',
        'precio' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'detalle_producto_id' => 'integer',
    ];

    public function detalleProducto(): BelongsTo
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function ObtenerProductosConCategorias()
    {
        // Cargar los detalles del producto con las relaciones necesarias
        $productos = DetalleProducto::with([
            'producto', 
            'producto.subcategoria.categoria', 
            'color', 
            'marca', 
            'material'
        ])
        ->where('estado', 1)
        ->whereNotIn('id', function ($query) {
            $query->select('detalleproducto_id')->from('precio_productos');
        })
        ->get()
        ->sortBy([
            function ($detalleProducto) {
                return $detalleProducto->producto->subcategoria->categoria->nombre;
            },
            function ($detalleProducto) {
                return $detalleProducto->producto->subcategoria->nombre;
            },
            function ($detalleProducto) {
                return $detalleProducto->producto->nombre;
            },
        ]);
    
        // Crear una estructura de resultados agrupados por categoría y subcategoría
        $resultados = [];
        foreach ($productos as $detalleProducto) {
            $categoriaNombre = $detalleProducto->producto->subcategoria->categoria->nombre;
            $subcategoriaNombre = $detalleProducto->producto->subcategoria->nombre;
    
            // Agrupar por categoría, luego por subcategoría
            $resultados[$categoriaNombre][$subcategoriaNombre][] = [
                'id' => $detalleProducto->id,
                'codigo' => $detalleProducto->codigo, 
                'idproducto' => $detalleProducto->producto->id,
                'nombre' => $detalleProducto->producto->nombre,
                'marca' => $detalleProducto->marca->nombre,
                'material' => $detalleProducto->material->nombre,
                'color' => $detalleProducto->color->nombre,
            ];
        }
    
        return $resultados;
    }
    
}
