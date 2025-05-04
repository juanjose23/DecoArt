<?php

// Verifica que la página de bienvenida cargue correctamente
test('la página de bienvenida carga correctamente', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

// Verifica que la página de login esté accesible
test('la página de login carga correctamente', function () {
    $response = $this->get('/auth/login');
    $response->assertStatus(200);
});

// Verifica que la ruta de redirección a Google devuelva una redirección
test('la ruta de redirección a Google devuelve una redirección', function () {
    $response = $this->get('/auth/google');
    $response->assertRedirect(); // Espera una redirección a Google
});

// Verifica que la ruta de callback de Google también responda con una redirección
test('la ruta de callback de Google responde con una redirección', function () {
    $response = $this->get('/auth/google/callback');
    $response->assertStatus(302)->assertRedirect(); // Generalmente redirige después de procesar el login
});

// Verifica que la ruta de logout acepte solicitudes POST y redirija
test('la ruta de logout redirige correctamente con POST', function () {
    $response = $this->post('/logout');
    $response->assertRedirect(); // Normalmente redirige al cerrar sesión
});

