<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Categoria;
use App\Models\Subcategoria;

class SubcategoriaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subcategoria::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'descripcion' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'categoria_id' => Categoria::factory(),
            'estado' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
