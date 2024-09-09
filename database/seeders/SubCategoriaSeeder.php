<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Obtén todas las categorías
        $categorias = DB::table('categorias')->pluck('id');

        // Define un array de subcategorías para cada categoría
        $subcategorias = [
            'Muebles' => [
                ['nombre' => 'Sillas', 'descripcion' => 'Sillas de diferentes estilos', 'estado' => 1],
                ['nombre' => 'Mesas', 'descripcion' => 'Mesas para comedor, salón y más', 'estado' => 1],
                ['nombre' => 'Sofás', 'descripcion' => 'Sofás y sillones confortables', 'estado' => 1],
            ],
            'Iluminación' => [
                ['nombre' => 'Lámparas de Techo', 'descripcion' => 'Lámparas para techos', 'estado' => 1],
                ['nombre' => 'Apliques', 'descripcion' => 'Apliques para paredes', 'estado' => 1],
                ['nombre' => 'Luces de Mesa', 'descripcion' => 'Luces decorativas de mesa', 'estado' => 1],
            ],
            'Textiles' => [
                ['nombre' => 'Cortinas', 'descripcion' => 'Cortinas para ventanas', 'estado' => 1],
                ['nombre' => 'Alfombras', 'descripcion' => 'Alfombras para todas las habitaciones', 'estado' => 1],
                ['nombre' => 'Cojines', 'descripcion' => 'Cojines decorativos para sofás y camas', 'estado' => 1],
            ],
            'Decoración de Paredes' => [
                ['nombre' => 'Cuadros', 'descripcion' => 'Cuadros decorativos para paredes', 'estado' => 1],
                ['nombre' => 'Espejos', 'descripcion' => 'Espejos decorativos', 'estado' => 1],
                ['nombre' => 'Vinilos Decorativos', 'descripcion' => 'Vinilos para decorar paredes', 'estado' => 1],
            ],
            'Accesorios de Cocina' => [
                ['nombre' => 'Utensilios de Cocina', 'descripcion' => 'Utensilios para cocinar', 'estado' => 1],
                ['nombre' => 'Jarras', 'descripcion' => 'Jarras para bebidas', 'estado' => 1],
                ['nombre' => 'Bandejas', 'descripcion' => 'Bandejas para servir', 'estado' => 1],
            ],
            'Organización y Almacenamiento' => [
                ['nombre' => 'Cajas', 'descripcion' => 'Cajas para almacenamiento', 'estado' => 1],
                ['nombre' => 'Cestas', 'descripcion' => 'Cestas para organizar', 'estado' => 1],
                ['nombre' => 'Organizadores de Armario', 'descripcion' => 'Organizadores para armarios', 'estado' => 1],
            ],
            'Jardinería Interior' => [
                ['nombre' => 'Macetas', 'descripcion' => 'Macetas para plantas de interior', 'estado' => 1],
                ['nombre' => 'Jardineras', 'descripcion' => 'Jardineras para interior', 'estado' => 1],
                ['nombre' => 'Sistemas de Riego', 'descripcion' => 'Sistemas para regar plantas', 'estado' => 1],
            ],
            'Decoración de Mesa' => [
                ['nombre' => 'Manteles', 'descripcion' => 'Manteles decorativos para mesas', 'estado' => 1],
                ['nombre' => 'Servilletas', 'descripcion' => 'Servilletas para mesa', 'estado' => 1],
                ['nombre' => 'Centros de Mesa', 'descripcion' => 'Centros decorativos para mesas', 'estado' => 1],
            ],
            'Ropa de Cama' => [
                ['nombre' => 'Sábanas', 'descripcion' => 'Sábanas para camas', 'estado' => 1],
                ['nombre' => 'Edredones', 'descripcion' => 'Edredones y cobijas', 'estado' => 1],
                ['nombre' => 'Fundas de Almohada', 'descripcion' => 'Fundas para almohadas', 'estado' => 1],
            ],
            'Accesorios de Baño' => [
                ['nombre' => 'Cortinas de Ducha', 'descripcion' => 'Cortinas para ducha', 'estado' => 1],
                ['nombre' => 'Alfombras de Baño', 'descripcion' => 'Alfombras para baño', 'estado' => 1],
                ['nombre' => 'Dispensadores de Jabón', 'descripcion' => 'Dispensadores para baño', 'estado' => 1],
            ],
        ];

        // Inserta las subcategorías en la base de datos
        foreach ($subcategorias as $categoriaNombre => $subcats) {
            $categoriaId = DB::table('categorias')->where('nombre', $categoriaNombre)->value('id');
            foreach ($subcats as $subcategoria) {
                DB::table('subcategorias')->insert([
                    'nombre' => $subcategoria['nombre'],
                    'descripcion' => $subcategoria['descripcion'],
                    'categoria_id' => $categoriaId,
                    'estado' => $subcategoria['estado'],
                ]);
            }
        }
    }

}
