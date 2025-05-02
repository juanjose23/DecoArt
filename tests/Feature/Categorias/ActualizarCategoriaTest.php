<?php
namespace Tests\Feature\Categorias;

use Database\Seeders\ShieldSeeder;
use App\Models\User;
use App\Models\Categorias;
use function Pest\Livewire\livewire;




it('puede actualizar una categoría', function () {
    $this->seed(ShieldSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);

    // Creamos una categoría existente
    $categoria = Categorias::factory()->create([
        'nombre' => 'Antigua Categoría',
        'estado' => 1,
        'descripcion' => 'Descripción antigua',
    ]);

    // Simulamos abrir el formulario de edición y actualizar
    livewire(\App\Filament\Resources\CategoriasResource\Pages\EditCategorias::class, [
        'record' => $categoria->getKey(), // Pasamos el ID del registro
    ])
        ->assertFormExists()
        ->fillForm([
            'nombre' => 'Categoría Actualizada',
            'estado' => 0,
            'descripcion' => 'Nueva descripción',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    // Verificamos que la categoría fue actualizada en la base de datos
    $this->assertDatabaseHas('categorias', [
        'id' => $categoria->id,
        'nombre' => 'Categoría Actualizada',
        'estado' => 0,
        'descripcion' => 'Nueva descripción',
    ]);
});
