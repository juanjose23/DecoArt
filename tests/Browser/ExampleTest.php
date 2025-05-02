<?php

use Laravel\Dusk\Browser;

test('basic example', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/admin/login')
            ->screenshot('01-login-page')
                ->assertSee('Decoart');
    });
});
