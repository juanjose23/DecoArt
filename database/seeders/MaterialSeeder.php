<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $materials = [
            ['nombre' => 'Madera', 'descripcion' => 'Material natural y duradero', 'estado' => 1],
            ['nombre' => 'Metal', 'descripcion' => 'Material robusto y resistente', 'estado' => 1],
            ['nombre' => 'Vidrio', 'descripcion' => 'Material transparente y elegante', 'estado' => 1],
            ['nombre' => 'Plástico', 'descripcion' => 'Material versátil y ligero', 'estado' => 1],
            ['nombre' => 'Cerámica', 'descripcion' => 'Material resistente y decorativo', 'estado' => 1],
            ['nombre' => 'Tela', 'descripcion' => 'Material suave y flexible', 'estado' => 1],
            ['nombre' => 'Cuero', 'descripcion' => 'Material lujoso y duradero', 'estado' => 1],
            ['nombre' => 'Piedra', 'descripcion' => 'Material sólido y natural', 'estado' => 1],
            ['nombre' => 'Mármol', 'descripcion' => 'Material elegante y resistente', 'estado' => 1],
            ['nombre' => 'Rattan', 'descripcion' => 'Material ligero y estilizado', 'estado' => 1],
        ];

        // Inserta los materiales en la base de datos
        DB::table('materials')->insert($materials);
    }
}
