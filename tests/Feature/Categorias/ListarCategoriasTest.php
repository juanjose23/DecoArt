<?php 
namespace Tests\Feature\Categorias;
use App\Filament\Resources\CategoriasResource;
use App\Filament\Resources\CategoriasResource\Pages\CreateCategorias;
use Database\Seeders\ShieldSeeder;
use App\Models\User;
use App\Models\Categorias;
use function Pest\Livewire\livewire;


it('Pueden listar las categoria', function () {
    $this->seed(ShieldSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);
    $categorias = Categorias::factory()->count(9)->create();

    livewire(CategoriasResource\Pages\ListCategorias::class)
    ->assertCanSeeTableRecords($categorias->take(9));
});





it('Puede validar el input', function () {
    $this->seed(ShieldSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);

    livewire(\App\Filament\Resources\CategoriasResource\Pages\CreateCategorias::class)
        ->assertFormExists()
        ->fillForm([
            'nombre' => '', 
            'estado' => null,
            'descripcion' => '', 
        ])
        ->call('create') // método heredado de CreateRecord
        ->assertHasFormErrors([
            'nombre' => 'required',
            'estado' => 'required',
            'descripcion' => 'required',
        ]);
});
