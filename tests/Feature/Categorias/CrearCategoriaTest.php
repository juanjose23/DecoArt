<?php
namespace Tests\Feature\Categorias;
use App\Filament\Resources\CategoriasResource;
use App\Filament\Resources\CategoriasResource\Pages\CreateCategorias;
use Database\Seeders\ShieldSeeder;

use App\Models\User;
use App\Models\Categorias;
use function Pest\Livewire\livewire;


it('Existe formulario', function () {
    $this->seed(ShieldSeeder::class); // 🔁 siembra los roles

    $user = User::factory()->create();
    $user->assignRole('super_admin');


    // Assuming 'super_admin' role has the required permissions
    $user->assignRole('super_admin');
    $this->actingAs($user);
    livewire(\App\Filament\Resources\CategoriasResource\Pages\CreateCategorias::class)
        ->assertFormExists();
});

it('puede llenar el formulario de categoría', function () {

    $this->seed(ShieldSeeder::class); // 🔁 siembra los roles

    $user = User::factory()->create();
    $user->assignRole('super_admin');


    // Assuming 'super_admin' role has the required permissions
    $user->assignRole('super_admin');
    $this->actingAs($user);
    $nombre = 'Categoría de pruebas';

    livewire(CreateCategorias::class)
        ->fillForm([
            'nombre' => $nombre,
            'estado' => 1,
            'descripcion' => 'Una descripción **válida**',
        ]);
});


