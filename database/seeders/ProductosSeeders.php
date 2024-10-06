<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $productos = [
            ['nombre' => 'Silla Moderna', 'descripcion' => 'Silla de diseño moderno', 'slug' => 'silla-moderna', 'caducidad' => false, 'subcategoria_id' => 1, 'estado' => 1],
            ['nombre' => 'Mesa de Comedor', 'descripcion' => 'Mesa amplia para comedor', 'slug' => 'mesa-comedor', 'caducidad' => false, 'subcategoria_id' => 2, 'estado' => 1],
            ['nombre' => 'Sofá Esquinero', 'descripcion' => 'Sofá esquinero cómodo', 'slug' => 'sofa-esquinero', 'caducidad' => false, 'subcategoria_id' => 3, 'estado' => 1],
            ['nombre' => 'Lámpara de Techo', 'descripcion' => 'Lámpara moderna para techos', 'slug' => 'lampara-techo', 'caducidad' => false, 'subcategoria_id' => 4, 'estado' => 1],
            ['nombre' => 'Alfombra de Estilo', 'descripcion' => 'Alfombra suave y decorativa', 'slug' => 'alfombra-estilo', 'caducidad' => false, 'subcategoria_id' => 5, 'estado' => 1],
            ['nombre' => 'Espejo Decorativo', 'descripcion' => 'Espejo elegante para paredes', 'slug' => 'espejo-decorativo', 'caducidad' => false, 'subcategoria_id' => 6, 'estado' => 1],
            ['nombre' => 'Cuadro Abstracto', 'descripcion' => 'Cuadro moderno para decoración', 'slug' => 'cuadro-abstracto', 'caducidad' => false, 'subcategoria_id' => 7, 'estado' => 1],
            ['nombre' => 'Cojines Decorativos', 'descripcion' => 'Cojines de diferentes estilos', 'slug' => 'cojines-decorativos', 'caducidad' => false, 'subcategoria_id' => 8, 'estado' => 1],
            ['nombre' => 'Cortinas Elegantes', 'descripcion' => 'Cortinas para dar privacidad y estilo', 'slug' => 'cortinas-elegantes', 'caducidad' => false, 'subcategoria_id' => 9, 'estado' => 1],
            ['nombre' => 'Plantas Artificiales', 'descripcion' => 'Plantas decorativas que no requieren cuidado', 'slug' => 'plantas-artificiales', 'caducidad' => false, 'subcategoria_id' => 10, 'estado' => 1],
        ];
        
        foreach ($productos as $producto) {
            $productoId = DB::table('productos')->insertGetId($producto);
            
            $combinacionesUsadas = []; 
        
            for ($i = 0; $i < 50; $i++) {
                
                do {
                    $colorId = rand(1, 10);
                    $marcaId = rand(1, 10);
                    $materialId = rand(1, 10);
                    $combinacion = "{$productoId}-{$colorId}-{$marcaId}-{$materialId}";
                } while (in_array($combinacion, $combinacionesUsadas));
        
                $combinacionesUsadas[] = $combinacion;
        
                $detalleId = DB::table('detalle_productos')->insertGetId([
                    'codigo' => 'P-' . strtoupper(substr(md5(rand()), 0, 6)), 
                    'producto_id' => $productoId,
                    'color_id' => $colorId,
                    'marca_id' => $marcaId, 
                    'material_id' => $materialId, 
                    'estado' => 1,
                ]);
                
                DB::table('precio_productos')->insert([
                    'detalleproducto_id' => $detalleId, 
                    'precio' => rand(100, 1000), 
                    'fecha_inicio' => now(), 
                    'fecha_fin' => now()->addMonths(1), 
                    'estado' => 1, 
                ]);
            }
        }
        
    }
}
