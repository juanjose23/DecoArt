<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Validator;
class RegistroUsuariosService
{
    protected $userModel;
    protected $mediaModel;
    protected $rolesUsuariosModel;
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;


    }

    /**
     * Registrar un nuevo usuario.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */

    public function register(array $data)
    {
       // Crear la validación para el correo electrónico
    $validator = Validator::make($data, [
        'email' => 'required|email|unique:users,email',
        // otras reglas de validación, como la contraseña, etc.
    ]);

    // Si la validación falla, lanzar una excepción de validación
    if ($validator->fails()) {
        throw new ValidationException($validator);
    }
        try {

            $user = $this->userModel->newInstance();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->estado = 1;

            // Validar y asignar contraseña
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();
            $user->sendEmailVerificationNotification();

            return $user;

        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            throw $e;
        }
    }

    public function buscarOcrearUsuario($providerUser, $provider)
    {
        switch ($provider) {
            case 'google':
                $userId =$providerUser['id'];
                $userNombre = $providerUser['given_name'];
                $userApellido = $providerUser['family_name'];
                $userEmail = $providerUser['email'];
                $userProfile = $providerUser['picture'];
        
                break;

            case 'twitter':
                $userId = $providerUser->getId();
                $userNombre = $providerUser->getName();
                $userEmail = $providerUser->getEmail();
                $userApellido='';
                $userProfile = $providerUser->getAvatar();
                break;

            case 'github':
                $userId = $providerUser->getId();
                $userNombre = $providerUser->getName();
                $userEmail = $providerUser->getEmail();
                $userApellido='';
                $userProfile = $providerUser->getAvatar();
                break;

            case 'microsoft':
                $userId = $providerUser->getId();
                $userNombre = $providerUser->getName();
                $userEmail = $providerUser->getEmail();
                $userApellido='';
                $userProfile = $providerUser->getAvatar();
                break;

            default:
                throw new \Exception('Proveedor no soportado');
        }

        
        $user = $this->userModel::where('provider', $provider)
            ->where('provider_id', $userId)
            ->first();

        if (!$user) {
            // Verificar si ya existe un usuario con el mismo correo electrónico
            $existeUser = User::where('email', $userEmail)->first();

            // Si ya existe un usuario con el mismo correo electrónico, devuelve ese usuario
            if ($existeUser) {
                return false;
            }
            return $this->CrearNuevoUsuario($providerUser, $provider);
        }

        // Si el usuario ya existe, actualiza sus datos
        $user->name = $userNombre . ' ' . $userApellido;
        $user->email = $userEmail;
        $user->avatar_url=$userProfile;
        $user->save();


       
   
      
        return $user;


    }
    public function CrearNuevoUsuario($providerUser, $provider)
    {
        switch ($provider) {
            case 'google': 
              
                $userId = $providerUser['id'];
                $userNombre = $providerUser['given_name'];
                $userApellido = $providerUser['family_name'];
                $userEmail = $providerUser['email'];
                $userProfile = $providerUser['picture'];
        
                break;

            case 'twitter':
                $userId = $providerUser->getId();
                $userNombre = $providerUser->getName();
                $userEmail = $providerUser->getEmail();
                $userApellido='';
                $userProfile = $providerUser->getAvatar();
                break;

            case 'github':
                $userId = $providerUser->getId();
                $userNombre = $providerUser->getName();
                $userEmail = $providerUser->getEmail();
                $userApellido='';
                $userProfile = $providerUser->getAvatar();
                break;

            case 'microsoft':
                $userId = $providerUser->getId();
                $userNombre = $providerUser->getName();
                $userEmail = $providerUser->getEmail();
                $userApellido='';
                $userProfile = $providerUser->getAvatar();
                break;

            default:
                throw new \Exception('Proveedor no soportado');
        }


        $user = $this->userModel->newInstance();
        $user->name = $userNombre . ' ' . $userApellido;
        $user->provider =$provider;
        $user->provider_id = $userId;
        $user->email = $userEmail;
        $user->password = bcrypt(Str::random(24));
        $user->estado = 1;
        $user->avatar_url=$userProfile;
        $user->save();



      

        return $user;
    }
    public function obtenerInformacionUsuario(int $userId): array
    {
        $user = $this->userModel->findOrFail($userId);
      
        $fotoPerfil = $user ? $user->avatar_url : $this->obtenerFotoPerfilEstatica($user->name);

        return [
            'nombre' => $user->name,
            'foto' => $fotoPerfil,
        ];
    }
    /**
     * Obtiene un enlace con una foto de perfil estática basada en la inicial del nombre de usuario.
     *
     * @param string $nombreUsuario El nombre del usuario del cual se desea obtener la foto de perfil.
     * @return string La URL de la foto de perfil estática.
     */
    public function obtenerFotoPerfilEstatica(string $nombreUsuario): string
    {
        $primerLetra = strtoupper(substr($nombreUsuario, 0, 1));
        return "https://ui-avatars.com/api/?name={$primerLetra}";
    }
    public function gestionarSesion(User $user)
    {
        // Validar usuario

        Auth::login($user, true);
        if ($user) {
            $userId = $user->id;
            $informacionPersonal = $this->obtenerInformacionUsuario($userId);
           # $privilegios = $this->obtenerPrivilegiosUsuario($userId);

            // Crear las sesiones
            Session::put('IdUser', $userId);
            Session::put('nombre', $informacionPersonal['nombre']);
            Session::put('Foto', $informacionPersonal['foto']);
           # Session::put('privilegios', $privilegios);
            // Preparar datos adicionales y encriptar payload
            $sessionData = [
                'IdUser' => $userId,
                'nombre' => $informacionPersonal['nombre'],
                'Foto' => $informacionPersonal['foto'],

            ];
            $request = request();
            $sessionId = $request->session()->getId();
            $payload = Crypt::encrypt(json_encode($sessionData));


            #return $this->obtenerRolValido($userId);
        } else {
            // Manejar caso en que el usuario no se valide
            throw new \Exception('Usuario no válido');
        }
    }
    public function logout($request)
    {
        // Cerrar la sesión del usuario
        Auth::logout();

      

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar el token de CSRF
        $request->session()->regenerateToken();
    }
 

}