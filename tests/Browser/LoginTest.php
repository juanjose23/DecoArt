<?php

use App\Models\User;
use Database\Seeders\ShieldSeeder;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;



test('login con credenciales inválidas', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/admin/login')
            ->type('input[type=email]', 'hola@gmail.com')
            ->type('input[type=password]', 'password')
            ->press('Entrar')
            ->waitForText('Estas credenciales no coinciden con nuestros registros.', 10)  
            ->assertPathIs('/admin/login') 
            ->screenshot('login_fallido');
    });
});




test('login con credenciales válidas', function () {

    $this->seed(ShieldSeeder::class);
    $user = \App\Models\User::factory()->create([
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/admin/login')
            ->type('input[type=email]', $user->email)
            ->type('input[type=password]', 'password')
            ->press('Entrar')
            ->waitForLocation('/admin')
            ->assertPathIs('/admin')
            ->screenshot('login_exitoso');
    });
});






