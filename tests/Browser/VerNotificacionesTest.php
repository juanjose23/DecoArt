<?php 
use Laravel\Dusk\Browser;
use Database\Seeders\ShieldSeeder;



test('login y presionar el botón de notificaciones', function () {
    $this->seed(ShieldSeeder::class);
    $user = \App\Models\User::factory()->create([
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    $this->browse(function (Browser $browser) use ($user) {
        // Iniciar sesión con credenciales válidas
        $browser->visit('/admin/login')
            ->type('input[type=email]', $user->email)
            ->type('input[type=password]', 'password')
            ->press('Entrar')
            ->waitForLocation('/admin')
            ->assertPathIs('/admin');

        // Hacer clic en el botón de notificaciones
        $browser->waitFor('.fi-topbar-database-notifications-btn', 10)
        ->click('.fi-topbar-database-notifications-btn')
        ->screenshot('01_notificaciones_click');    

        $browser->waitForText('No hay notificaciones', 10)
        ->screenshot('02_modal_abierto');
  
    });
});


