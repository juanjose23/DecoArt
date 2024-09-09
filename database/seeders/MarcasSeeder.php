<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $marcas = [
            ['nombre' => 'DecoArt', 'descripcion' => 'Accesorios y decoraciones de alta calidad', 'estado' => 1],
            ['nombre' => 'HomeElegance', 'descripcion' => 'Elegancia y diseño para el hogar', 'estado' => 1],
            ['nombre' => 'StyleHaus', 'descripcion' => 'Estilo moderno para cada rincón de tu casa', 'estado' => 1],
            ['nombre' => 'LuxDesign', 'descripcion' => 'Diseños de lujo para el hogar', 'estado' => 1],
            ['nombre' => 'VivaDecor', 'descripcion' => 'Decoraciones vibrantes para cada espacio', 'estado' => 1],
            ['nombre' => 'EcoHome', 'descripcion' => 'Accesorios ecológicos para el hogar', 'estado' => 1],
            ['nombre' => 'UrbanChic', 'descripcion' => 'Diseños urbanos y chic para interiores modernos', 'estado' => 1],
            ['nombre' => 'ClassicTouch', 'descripcion' => 'Toques clásicos para una decoración atemporal', 'estado' => 1],
            ['nombre' => 'FreshVibe', 'descripcion' => 'Ambiente fresco y moderno para tu hogar', 'estado' => 1],
            ['nombre' => 'HomeZen', 'descripcion' => 'Diseños para un hogar relajante y sereno', 'estado' => 1],
        ];

        // Inserta las marcas en la base de datos
        DB::table('marcas')->insert($marcas);
    }
}
