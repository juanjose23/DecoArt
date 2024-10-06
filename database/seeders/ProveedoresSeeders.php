<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProveedoresSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $proveedores = [
            [
                'nombre' => 'Proveedor Uno',
                'correo' => 'proveedor1@example.com',
                'telefono' => '0810112821000F',
                'sector_comercial' => 'Muebles',
                'razon_social' => 'Muebles y Más S.A.',
                'ruc' => '0810112821000F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Dos',
                'correo' => 'proveedor2@example.com',
                'telefono' => '0810112821001F',
                'sector_comercial' => 'Iluminación',
                'razon_social' => 'Luces Brillantes S.A.',
                'ruc' => '0810112821001F',
                'estado' => 1,
            ],
            // Agrega 8 proveedores más
            [
                'nombre' => 'Proveedor Tres',
                'correo' => 'proveedor3@example.com',
                'telefono' => '0810112821002F',
                'sector_comercial' => 'Decoración',
                'razon_social' => 'Decoraciones Elegantes S.A.',
                'ruc' => '0810112821002F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Cuatro',
                'correo' => 'proveedor4@example.com',
                'telefono' => '0810112821003F',
                'sector_comercial' => 'Textiles',
                'razon_social' => 'Textiles Finos S.A.',
                'ruc' => '0810112821003F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Cinco',
                'correo' => 'proveedor5@example.com',
                'telefono' => '0810112821004F',
                'sector_comercial' => 'Accesorios',
                'razon_social' => 'Accesorios Únicos S.A.',
                'ruc' => '0810112821004F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Seis',
                'correo' => 'proveedor6@example.com',
                'telefono' => '0810112821005F',
                'sector_comercial' => 'Muebles',
                'razon_social' => 'Muebles & Diseño S.A.',
                'ruc' => '0810112821005F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Siete',
                'correo' => 'proveedor7@example.com',
                'telefono' => '0810112821006F',
                'sector_comercial' => 'Iluminación',
                'razon_social' => 'Brillantes S.A.',
                'ruc' => '0810112821006F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Ocho',
                'correo' => 'proveedor8@example.com',
                'telefono' => '0810112821007F',
                'sector_comercial' => 'Decoración',
                'razon_social' => 'Decoración Casa S.A.',
                'ruc' => '0810112821007F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Nueve',
                'correo' => 'proveedor9@example.com',
                'telefono' => '0810112821008F',
                'sector_comercial' => 'Textiles',
                'razon_social' => 'Textiles de Calidad S.A.',
                'ruc' => '0810112821008F',
                'estado' => 1,
            ],
            [
                'nombre' => 'Proveedor Diez',
                'correo' => 'proveedor10@example.com',
                'telefono' => '0810112821009F',
                'sector_comercial' => 'Accesorios',
                'razon_social' => 'Accesorios Creativos S.A.',
                'ruc' => '0810112821009F',
                'estado' => 1,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            DB::table('proveedors')->insert($proveedor);
        }
        $proveedores = DB::table('proveedors')->pluck('id');

        foreach ($proveedores as $proveedorId) {
            DB::table('contacto_proveedors')->insert([
                'nombre' => 'Contacto de Proveedor ' . $proveedorId,
                'proveedor_id' => $proveedorId,
                'estado' => 1,
            ]);
        }
    }
}
