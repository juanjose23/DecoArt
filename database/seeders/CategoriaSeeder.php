<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categorias')->insert([
            ['nombre' => 'Muebles', 'descripcion' => 'Incluye sillas, mesas, sofás, estanterías, etc.', 'estado' => 1],
            ['nombre' => 'Iluminación', 'descripcion' => 'Lámparas, apliques, luces de techo, luces de mesa, etc.', 'estado' => 1],
            ['nombre' => 'Textiles', 'descripcion' => 'Cortinas, alfombras, cojines, mantas, etc.', 'estado' => 1],
            ['nombre' => 'Decoración de Paredes', 'descripcion' => 'Cuadros, espejos, vinilos decorativos, relojes de pared, etc.', 'estado' => 1],
            ['nombre' => 'Accesorios de Cocina', 'descripcion' => 'Utensilios, jarras, bandejas, cortinas de cocina, etc.', 'estado' => 1],
            ['nombre' => 'Organización y Almacenamiento', 'descripcion' => 'Cajas, cestas, organizadores de armario, estantes, etc.', 'estado' => 1],
            ['nombre' => 'Jardinería Interior', 'descripcion' => 'Macetas, jardineras, sistemas de riego, herramientas de jardinería, etc.', 'estado' => 1],
            ['nombre' => 'Decoración de Mesa', 'descripcion' => 'Manteles, servilletas, centros de mesa, platos decorativos, etc.', 'estado' => 1],
            ['nombre' => 'Ropa de Cama', 'descripcion' => 'Sábanas, edredones, fundas de almohada, fundas nórdicas, etc.', 'estado' => 1],
            ['nombre' => 'Accesorios de Baño', 'descripcion' => 'Cortinas de ducha, alfombras de baño, dispensadores de jabón, portacepillos, etc.', 'estado' => 1],
        ]);
    }
}
