<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Colore;
use App\Models\DetalleProducto;
use App\Models\Marca;
use App\Models\Materiale;
use App\Models\Producto;

class DetalleProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetalleProducto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'producto_id' => Producto::factory(),
            'color_id' => Colore::factory(),
            'marca_id' => Marca::factory(),
            'material_id' => Materiale::factory(),
            'estado' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
