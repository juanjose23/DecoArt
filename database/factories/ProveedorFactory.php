<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Proveedor;

class ProveedorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proveedor::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'correo' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'telefono' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'sector_comercial' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'razon_social' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'ruc' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'estado' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
