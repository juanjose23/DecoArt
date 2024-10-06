<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

</head>


<body class="font-inter overflow-hidden">
    <section class="flex justify-center relative">
        <img src="{{asset('carrusel_1.jpeg')}}" alt="gradient background image" class="w-full h-full object-cover fixed">
        <div class="mx-auto max-w-lg px-6 lg:px-8 absolute py-20">
      
          <div class="rounded-2xl bg-white shadow-xl">
            <form action="" class="lg:p-11 p-7 mx-auto">
              <div class="mb-11">
                <h1 class="text-gray-900 text-center font-manrope text-3xl font-bold leading-10 mb-2">Welcome Back</h1>
              
              </div>
      
              <!-- Botón de Google -->
              <a href="{{ route('auth.google') }}" class="w-full h-12 mb-6 bg-[#E9EDF7] text-gray-900 text-base font-medium leading-6 flex items-center justify-center rounded-full shadow-sm hover:bg-gray-300 transition-all duration-300">
                <img class="h-5 mr-2" src="https://raw.githubusercontent.com/Loopple/loopple-public-assets/main/motion-tailwind/img/logos/logo-google.png" alt="Google logo">
                Sign in with Google
              </a>
      
              <input type="text" class="w-full h-12 text-gray-900 placeholder:text-gray-400 text-lg font-normal leading-7 rounded-full border-gray-300 border shadow-sm focus:outline-none px-4 mb-6" placeholder="Username">
              <input type="text" class="w-full h-12 text-gray-900 placeholder:text-gray-400 text-lg font-normal leading-7 rounded-full border-gray-300 border shadow-sm focus:outline-none px-4 mb-1" placeholder="Password">
              
              <a href="javascript:;" class="flex justify-end mb-6">
                <span class="text-indigo-600 text-right text-base font-normal leading-6">Forgot Password?</span>
              </a>
              
              <button class="w-full h-12 text-white text-center text-base font-semibold leading-6 rounded-full hover:bg-indigo-800 transition-all duration-700 bg-indigo-600 shadow-sm mb-11">Login</button>
              
              <a href="javascript:;" class="flex justify-center text-gray-900 text-base font-medium leading-6"> Don’t have an account? 
                <span class="text-indigo-600 font-semibold pl-3"> Sign Up</span>
              </a>
            </form>
          </div>
        </div>
      </section>
      

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/js/pagedone.js"></script>
    <script src="js/util.js"></script>
</body>

</html>