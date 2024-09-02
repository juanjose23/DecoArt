<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ContactoProveedor;
use App\Models\Proveedore;

class ContactoProveedorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactoProveedor::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{120}'),
            'proveedor_id' => Proveedore::factory(),
            'estado' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
