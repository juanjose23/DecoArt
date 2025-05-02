<?php

use Laravel\Dusk\Browser;
use Database\Seeders\ShieldSeeder;


test('logout y redirigir al login', function () {
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

        $browser->click('.fi-dropdown-trigger') // avatar
            ->waitFor('.fi-dropdown-panel')
            ->screenshot('menu_abierto')
            ->press('Salir')
            ->waitForLocation('/admin/login')
            ->assertPathIs('/admin/login')
            ->screenshot('logout_exitoso');

    });
});
