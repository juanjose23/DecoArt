<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DetalleProducto;
use App\Models\PrecioProducto;

class PrecioProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrecioProducto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'detalleproducto_id' => DetalleProducto::factory(),
            'precio' => $this->faker->randomFloat(2, 0, 99999999.99),
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'estado' => $this->faker->numberBetween(-10000, 10000),
            'detalle_producto_id' => DetalleProducto::factory(),
        ];
    }
}
