<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-md mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Credenciales de Usuario</h1>
            
            <div class="space-y-4">
                <div>
                    <h2 class="text-sm font-medium text-gray-500">Nombre</h2>
                    <p class="mt-1 text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                </div>
                
                <div>
                    <h2 class="text-sm font-medium text-gray-500">Correo</h2>
                    <p class="mt-1 text-lg font-semibold text-gray-800">{{ $user->email }}</p>
                </div>
                
              
                <div>
                    <h2 class="text-sm font-medium text-gray-500">Contraseña</h2>
                    <p class="mt-1 text-lg font-semibold text-gray-800">{{ $user->password }}</p>
                </div>
             
                <div>
                    <h2 class="text-sm font-medium text-gray-500">Fecha de Creación</h2>
                    <p class="mt-1 text-lg font-semibold text-gray-800">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                
            </div>
        </div>
    </div>
</body>
</html>