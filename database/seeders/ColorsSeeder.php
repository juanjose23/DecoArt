<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $colors = [
            ['nombre' => 'Rojo', 'codigo' => '#FF0000', 'estado' => 1],
            ['nombre' => 'Verde', 'codigo' => '#00FF00', 'estado' => 1],
            ['nombre' => 'Azul', 'codigo' => '#0000FF', 'estado' => 1],
            ['nombre' => 'Amarillo', 'codigo' => '#FFFF00', 'estado' => 1],
            ['nombre' => 'Negro', 'codigo' => '#000000', 'estado' => 1],
            ['nombre' => 'Blanco', 'codigo' => '#FFFFFF', 'estado' => 1],
            ['nombre' => 'Gris', 'codigo' => '#808080', 'estado' => 1],
            ['nombre' => 'Naranja', 'codigo' => '#FFA500', 'estado' => 1],
            ['nombre' => 'Rosa', 'codigo' => '#FFC0CB', 'estado' => 1],
            ['nombre' => 'Marrón', 'codigo' => '#A52A2A', 'estado' => 1],
        ];

        // Inserta los colores en la base de datos
        DB::table('colors')->insert($colors);
    }
}
