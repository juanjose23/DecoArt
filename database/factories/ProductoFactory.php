<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Producto;
use App\Models\Subcategoria;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'descripcion' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'caducidad' => $this->faker->boolean(),
            'subcategoria_id' => Subcategoria::factory(),
            'estado' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
