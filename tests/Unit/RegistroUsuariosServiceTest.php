<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\RegistroUsuariosService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class RegistroUsuariosServiceTest extends TestCase
{
    public function testRegisterService()
    {
        // Mock del servicio con expectativas sobre métodos
        $registroServiceMock = Mockery::mock(RegistroUsuariosService::class, function ($mock) {  #type:ignore
            // Simular el comportamiento del método register
            $mock->shouldReceive('register')
                ->once()
                ->with(['name' => 'John', 'email' => 'john@example.com'])
                ->andReturn(new User());
        });
    
        // Usar el mock en el contenedor de Laravel
        $this->app->instance(RegistroUsuariosService::class, $registroServiceMock);
    
        // Llamar al servicio y verificar el comportamiento
        $registroServiceMock->register(['name' => 'John', 'email' => 'john@example.com']);
    }
    

    public function testRegisterUsuarioExitoso()
    {
        // Simular los datos de entrada
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        // Crear una instancia del servicio
        $registroService = new RegistroUsuariosService(new User());

        // Ejecutar el método de registro
        $user = $registroService->register($data);

        // Verificar que el usuario fue creado y sus datos
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function testRegistroEmailDuplicado()
    {
        // Crear un usuario existente
        $existingUser = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            'estado' => 1,
        ]);

        // Simular los datos de entrada con un correo electrónico duplicado
        $data = [
            'name' => 'John Doe',
            'email' => 'jane@example.com', // Email duplicado
            'password' => 'password123',
            'estado' => 1,
        ];

        // Crear una instancia del servicio
        $registroService = new RegistroUsuariosService(new User());

        // Intentar registrar el usuario y verificar la excepción
        try {
            $registroService->register($data);
            $this->fail('Se esperaba una excepción de validación por correo electrónico duplicado');
        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->fails());
            $this->assertArrayHasKey('email', $e->validator->errors()->toArray());
        }
    }


    public function testCrearUsuarioConGoogle()
    {
        // Simular la información del usuario de Google
        $providerUser = [
            'id' => 'google-id-123',
            'given_name' => 'Boris',
            'family_name' => 'Boris',
            'email' => 'Boris@example.com',
            'picture' => 'https://example.com/avatar.jpg',
        ];
        $provider = 'google';

        // Crear una instancia del servicio
        $registroService = new RegistroUsuariosService(new User());

        // Llamar a la función para buscar o crear el usuario
        $user = $registroService->buscarOcrearUsuario($providerUser, $provider);

        // Verificar que el usuario fue creado correctamente
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Boris Boris', $user->name);
        $this->assertEquals('Boris@example.com', $user->email);
        $this->assertEquals('google-id-123', $user->provider_id);
        $this->assertEquals('google', $user->provider);
        $this->assertEquals('https://example.com/avatar.jpg', $user->avatar_url);
    }
    
}
