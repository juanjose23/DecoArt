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

<body>
    <nav class="bg-[#364B60] text-white">
        <div class="hidden md:block">
            <div class="max-w-screen-xl mx-auto px-4 py-2 flex items-center justify-between">
                <a href="/" class="flex-shrink-0">
                    <img src="https://res.cloudinary.com/dxtlbsa62/image/upload/v1717962322/Verdies/srx3xflk0atk71jzrmdq.png"
                        alt="" class="h-8">
                </a>
                <div class="flex items-center text-sm ml-4">
                    <i class="fas fa-map-marker-alt text-lg mr-1"></i>
                    <div>
                        <div class="text-xs text-gray-300">Entrega en toda</div>
                        <div class="font-bold">Nicaragua</div>
                    </div>
                </div>
                <div class="flex-grow mx-4">
                    <form class="flex">
                        <div class="relative">
                            <select
                                class="appearance-none bg-gray-100 border-r border-gray-300 text-black rounded-l-md pl-4 pr-6 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 ">
                                <option>Todas las categorías</option>
                                <option>Electrónicos</option>
                                <option>Computadoras</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                        <input type="text" class="flex-grow px-4 py-2 text-black" placeholder="Buscar ">
                        <button type="submit" class="bg-[#febd69] hover:bg-[#f3a847] text-black px-4 rounded-r-md">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="flex items-center space-x-6 text-sm">
                    <div class="dropdown relative inline-flex">
                        @guest


                            <button type="button" data-target="dropdown-default-main"
                                class="dropdown-toggle inline-flex justify-center items-center gap-2 py-3 px-6 text-sm text-white rounded-full cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 ">
                                Identificate <svg class="dropdown-open:rotate-180 w-2.5 h-2.5 text-white" width="16"
                                    height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </button>


                            <div id="dropdown-default-main"
                                class="dropdown-menu rounded-xl shadow-lg bg-white absolute top-full w-72 mt-2 hidden z-50"
                                aria-labelledby="dropdown-default-main">
                                <ul class="py-2">
                                    <li class="text-center">



                                        <button
                                            class="btn btn-primary bg-[#C19362]  rounded-full mx-auto px-6 py-2 text-white-900 font-semibold">
                                            Identificarse
                                        </button>
                                        <span class="block px-6 py-1 text-gray-600 text-xs">
                                            ¿Eres un cliente nuevo? <a href=""
                                                class="text-blue-500 hover:underline">Empieza
                                                aquí.</a>
                                        </span>



                                    </li>

                                    <li class="flex items-center mt-1">
                                        <a class="px-1 py-2 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Ver Accesorios
                                        </a>
                                        <div class="border-l h-8 mx-1"></div>
                                        <a class="px-1 py-2 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Mis Compras
                                        </a>
                                    </li>
                                    <li class="flex items-center mt-1">
                                        <a class="px-1 py-2 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Mi Cuenta
                                        </a>
                                        <div class="border-l h-8 mx-1"></div>
                                        <a class="px-1 py-2 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Mis Pedidos
                                        </a>
                                    </li>
                                    <li class="flex items-center mt-1">
                                        <a class="px-1 py-2 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Mis Favoritos
                                        </a>
                                        <div class="border-l h-8 mx-2"></div>
                                        <a class="px-1 py-1 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Registro de Clientes
                                        </a>
                                    </li>


                                </ul>
                            </div>
                        @else
                            <button type="button" data-target="dropdown-default-main"
                                class="dropdown-toggle inline-flex justify-center items-center gap-2 py-3 px-6 text-sm text-white rounded-full cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 ">
                                {{Session::get('nombre')}} <svg class="dropdown-open:rotate-180 w-2.5 h-2.5 text-white" width="16"
                                    height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </button>


                            <div id="dropdown-default-main"
                                class="dropdown-menu rounded-xl shadow-lg bg-white absolute top-full w-72 mt-2 hidden z-50"
                                aria-labelledby="dropdown-default-main">
                                <ul class="py-2">
                        

                                   
                                    <li class="flex items-center mt-1">
                                        <a class="px-1 py-2 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Mi Cuenta
                                        </a>
                                        <div class="border-l h-8 mx-1"></div>
                                        <a class="px-1 py-2 text-gray-600 font-medium w-1/2 text-center"
                                            href="javascript:;">
                                            Mis Pedidos
                                        </a>
                                    </li>
                                    <li class="flex items-center mt-1">
                                       
                                        <div class="border-l h-8 mx-2"></div>
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button class="btn btn-primary bg-[#C19362] rounded-full mx-auto px-6 py-2 text-white-900 font-semibold" type="submit">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>


                                </ul>
                            </div>
                        @endguest

                    </div>


                    <div>
                        <div>Devoluciones</div>
                        <div class="font-bold">y Pedidos</div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart text-2xl"></i>

                        <span class="font-bold ml-1">Carrito</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- Mobile Navbar -->
        <div class="md:hidden">
            <div class="flex items-center justify-between px-4 py-2">
                <button aria-label="Menú" class="drawer-button  text-2xl" type="button"
                    data-drawer-target="drawer-left-example" data-drawer-show="drawer-left-example"
                    data-drawer-position="left" aria-controls="drawer-left-example">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="/" class="flex-shrink-0">
                    <img src="https://res.cloudinary.com/dxtlbsa62/image/upload/v1717962322/Verdies/srx3xflk0atk71jzrmdq.png"
                        alt="Amazon.com.mx" class="h-8">
                </a>
                <div class="flex items-center space-x-3">
                    <button aria-label="Cuenta de usuario" class="text-2xl">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="relative">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-[#f08804] text-black text-xs rounded-full h-4 w-4 flex items-center justify-center">0</span>
                    </div>
                </div>
            </div>
            <div class="px-3 pb-2 w-full">
                <form class="flex">
                    <input type="text" class="flex-grow px-4 py-2 text-black rounded-l-md" placeholder="Buscar ">
                    <button type="submit" class="bg-[#febd69] hover:bg-[#f3a847] text-black px-4 rounded-r-md">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="bg-[#232f3e] py-2">
            <div class="max-w-screen-xl mx-auto px-4 flex items-center justify-between">

                <button
                    class="hidden md:flex drawer-button  py-2 items-center focus:outline-none focus:ring-2 focus:ring-white mr-4"
                    type="button" data-drawer-target="drawer-left-example" data-drawer-show="drawer-left-example"
                    data-drawer-position="left" aria-controls="drawer-left-example">
                    <i class="fas fa-bars mr-2" aria-hidden="true"></i>
                    <span class="font-bold">Todo</span>
                </button>

                <!-- Main categories -->
                <div class="hidden md:flex space-x-4">
                    <a href="#" class="hover:underline">Ofertas del Día</a>
                    <a href="#" class="hover:underline">Servicio al Cliente</a>
                    <a href="#" class="hover:underline">Listas</a>
                    <a href="#" class="hover:underline">Tarjetas de Regalo</a>
                    <a href="#" class="hover:underline">Vender</a>
                </div>

                <!-- Shop deals in Electronics -->
                <div class="hidden lg:block ml-auto pr-8"> <!-- Aumentar margen derecho -->
                    <a href="#" class="font-bold hover:underline">Comprar ofertas en Electrónica</a>
                </div>
            </div>


            <!-- Mobile menu -->
            <div id="marquee" class="marquee block md:hidden overflow-x-auto whitespace-nowrap">
                <div class="flex space-x-4">
                    <a href=""></a>
                    <a href="#" class="hover:underline">Ofertas del Día</a>
                    <a href="#" class="hover:underline">Servicio al Cliente</a>
                    <a href="#" class="hover:underline">Listas</a>
                    <a href="#" class="hover:underline">Tarjetas de Regalo</a>
                    <a href="#" class="hover:underline">Vender </a>
                    <a href="#" class="hover:underline text-hidden"> </a>
                </div>
            </div>



        </div>

    </nav>

    <div class="w-full h-[calc(100vh-100px)] relative">
        <div class="swiper default-carousel swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="bg-indigo-50 flex justify-center items-center h-[calc(100vh-100px)]">

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="bg-indigo-50 flex justify-center items-center h-[calc(100vh-200px)]">

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="bg-indigo-50 flex justify-center items-center h-[calc(100vh-100px)]">

                    </div>
                </div>
            </div>

            <div class="swiper-pagination"></div>
        </div>
    </div>
    <section>
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
            <header class="text-center">
                <h2 class="text-xl font-bold text-gray-900 sm:text-3xl">New Collection</h2>

                <p class="mx-auto mt-4 max-w-md text-gray-500">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Itaque praesentium cumque iure
                    dicta incidunt est ipsam, officia dolor fugit natus?
                </p>
            </header>

            <ul class="mt-8 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <li>
                    <a href="#" class="group relative block">
                        <img src="{{ asset('carrusel_1.jpeg') }}" alt=""
                            class="aspect-square w-full object-cover transition duration-500 group-hover:opacity-90" />

                        <div class="absolute inset-0 flex flex-col items-start justify-end p-6">
                            <h3 class="text-xl font-medium text-white">Casual Trainers</h3>

                            <span
                                class="mt-1.5 inline-block bg-black px-5 py-3 text-xs font-medium uppercase tracking-wide text-white">
                                Shop Now
                            </span>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="#" class="group relative block">
                        <img src="{{ asset('carrusel_1.jpeg') }}" alt=""
                            class="aspect-square w-full object-cover transition duration-500 group-hover:opacity-90" />

                        <div class="absolute inset-0 flex flex-col items-start justify-end p-6">
                            <h3 class="text-xl font-medium text-white">Winter Jumpers</h3>

                            <span
                                class="mt-1.5 inline-block bg-black px-5 py-3 text-xs font-medium uppercase tracking-wide text-white">
                                Shop Now
                            </span>
                        </div>
                    </a>
                </li>

                <li class="lg:col-span-2 lg:col-start-2 lg:row-span-2 lg:row-start-1">
                    <a href="#" class="group relative block">
                        <img src="{{ asset('carrusel_1.jpeg') }}" alt=""
                            class="aspect-square w-full object-cover transition duration-500 group-hover:opacity-90" />

                        <div class="absolute inset-0 flex flex-col items-start justify-end p-6">
                            <h3 class="text-xl font-medium text-white">Skinny Jeans Blue</h3>

                            <span
                                class="mt-1.5 inline-block bg-black px-5 py-3 text-xs font-medium uppercase tracking-wide text-white">
                                Shop Now
                            </span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <section class="py-1 ">
        <div class="w-full max-w-7xl px-6 lg:px-8 mx-auto">
            <div class="flex items-center justify-center flex-col gap-5 mb-14">

                <h2 class="font-manrope font-bold text-4xl text-gray-900 text-center">Elegancia en Decoración</h2>
                <p class="text-lg font-normal text-gray-500 max-w-3xl mx-auto text-center">
                    En el mundo de la decoración del hogar, cada elemento cuenta una historia y crea un ambiente único y
                    acogedor.
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mb-14">
                <div class="sm:col-span-2 bg-cover bg-center max-md:h-80 rounded-lg flex justify-end flex-col px-7 py-6"
                    style="background-image: url(https://pagedone.io/asset/uploads/1707712993.png);">
                    <h6 class="font-medium text-xl leading-8 text-white mb-4">Diseñador de Interiores</h6>
                    <p class="text-base font-normal text-white/70">Transformando espacios con creatividad y estilo para
                        cada hogar.</p>
                </div>
                <div class="block">
                    <img src="https://pagedone.io/asset/uploads/1707713007.png" alt="Decoración del hogar"
                        class="w-full rounded-lg object-cover">
                </div>
                <div class="block">
                    <img src="https://pagedone.io/asset/uploads/1707713018.png" alt="Decoración del hogar"
                        class="w-full rounded-lg object-cover">
                </div>
                <div class="block">
                    <img src="https://pagedone.io/asset/uploads/1707713032.png" alt="Decoración del hogar"
                        class="w-full rounded-lg object-cover">
                </div>
                <div class="bg-cover rounded-lg max-sm:h-80 flex justify-start flex-col px-7 py-6"
                    style="background-image: url(https://pagedone.io/asset/uploads/1707713043.png);">
                    <h6 class="font-medium text-xl leading-8 text-white mb-4">Decorador de Espacios</h6>
                    <p class="text-base font-normal text-white/70">Creando ambientes acogedores donde la funcionalidad
                        se encuentra con la belleza.</p>
                </div>
                <div class="block">
                    <img src="https://pagedone.io/asset/uploads/1707713055.png" alt="Decoración del hogar"
                        class="w-full rounded-lg object-cover">
                </div>
                <div class="bg-cover rounded-lg max-sm:h-80 flex justify-end flex-col px-7 py-6"
                    style="background-image: url(https://pagedone.io/asset/uploads/1707713066.png);">
                    <h6 class="font-medium text-xl leading-8 text-white mb-4">Estilo de Vida</h6>
                    <p class="text-base font-normal text-white/70">Diseñando espacios que reflejan tu personalidad y
                        mejoran tu calidad de vida.</p>
                </div>
            </div>

        </div>
    </section>

    <footer class="w-full">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Grid -->
            <div
                class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-8 py-10 max-sm:max-w-sm max-sm:mx-auto gap-y-8">
                <div class="col-span-full mb-10 lg:col-span-2 lg:mb-0">
                    <a href="index.html" class="flex justify-center lg:justify-start">
                        DecoArts
                    </a>
                    <p class="py-8 text-sm text-gray-500 lg:max-w-xs text-center lg:text-left">Confiado en más de 100
                        países y 5 millones de clientes. ¿Tienes alguna consulta?</p>
                    <a href="javascript:;"
                        class="py-2.5 px-5 h-9 block w-fit bg-indigo-600 rounded-full shadow-sm text-xs text-white mx-auto transition-all duration-500 hover:bg-indigo-700 lg:mx-0">
                        Contáctanos
                    </a>
                </div>
                <!-- End Col -->
                <div class="lg:mx-auto text-left">
                    <h4 class="text-lg text-gray-900 font-medium mb-7">Acerca de</h4>
                    <ul class="text-sm transition-all duration-500">
                        <li class="mb-6"><a href="javascript:;"
                                class="text-gray-600 hover:text-gray-900">Inicio</a>
                        </li>
                        <li class="mb-6"><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Acerca
                                de</a>
                        </li>
                        <li class="mb-6"><a href="javascript:;"
                                class="text-gray-600 hover:text-gray-900">Precios</a>
                        </li>
                        <li><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Características</a></li>
                    </ul>
                </div>
                <!-- End Col -->
                <div class="lg:mx-auto text-left">
                    <h4 class="text-lg text-gray-900 font-medium mb-7">Productos</h4>
                    <ul class="text-sm transition-all duration-500">
                        <li class="mb-6"><a href="product.html"
                                class="text-gray-600 hover:text-gray-900">Categorias</a>
                        </li>
                        <li class="mb-6"><a href="product.html"
                                class="text-gray-600 hover:text-gray-900">Marcas</a>
                        </li>
                        <li class="mb-6"><a href="product.html"
                                class="text-gray-600 hover:text-gray-900">Estilos</a>
                        </li>

                    </ul>
                </div>
                <!-- End Col -->
                <div class="lg:mx-auto text-left">
                    <h4 class="text-lg text-gray-900 font-medium mb-7">Recursos</h4>
                    <ul class="text-sm transition-all duration-500">
                        <li class="mb-6"><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Preguntas
                                Frecuentes</a></li>
                        <li class="mb-6"><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Inicio
                                Rápido</a></li>
                        <li class="mb-6"><a href="javascript:;"
                                class="text-gray-600 hover:text-gray-900">Documentación</a></li>
                        <li><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Guía del Usuario</a></li>
                    </ul>
                </div>
                <!-- End Col -->
                <div class="lg:mx-auto text-left">
                    <h4 class="text-lg text-gray-900 font-medium mb-7">Blogs</h4>
                    <ul class="text-sm transition-all duration-500">
                        <li class="mb-6"><a href="javascript:;"
                                class="text-gray-600 hover:text-gray-900">Noticias</a>
                        </li>
                        <li class="mb-6"><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Consejos
                                y
                                Trucos</a></li>
                        <li class="mb-6"><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Nuevas
                                Actualizaciones</a></li>
                        <li><a href="javascript:;" class="text-gray-600 hover:text-gray-900">Eventos</a></li>
                    </ul>
                </div>
            </div>
            <!-- Grid -->
            <div class="py-7 border-t border-gray-200">
                <div class="flex items-center justify-center flex-col lg:justify-between lg:flex-row">

                    <span class="text-sm text-gray-500 ">©<a href="https://github.com/juanjose23/">DecoArts</a> 2024,
                        Todos los derechos reservados.</span>
                    <div class="flex mt-4 space-x-4 sm:justify-center lg:mt-0 ">
                        <a href="javascript:;"
                            class="w-9 h-9 rounded-full bg-gray-700 flex justify-center items-center hover:bg-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 20 20" fill="none">
                                <g id="Social Media">
                                    <path id="Vector"
                                        d="M11.3214 8.93666L16.4919 3.05566H15.2667L10.7772 8.16205L7.1914 3.05566H3.05566L8.47803 10.7774L3.05566 16.9446H4.28097L9.022 11.552L12.8088 16.9446H16.9446L11.3211 8.93666H11.3214ZM9.64322 10.8455L9.09382 10.0765L4.72246 3.95821H6.60445L10.1322 8.8959L10.6816 9.66481L15.2672 16.083H13.3852L9.64322 10.8458V10.8455Z"
                                        fill="white" />
                                </g>
                            </svg>
                        </a>
                        <a href="javascript:;"
                            class="w-9 h-9 rounded-full bg-gray-700 flex justify-center items-center hover:bg-indigo-600">
                            <svg class="w-[1.25rem] h-[1.125rem] text-white" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.70975 7.93663C4.70975 6.65824 5.76102 5.62163 7.0582 5.62163C8.35537 5.62163 9.40721 6.65824 9.40721 7.93663C9.40721 9.21502 8.35537 10.2516 7.0582 10.2516C5.76102 10.2516 4.70975 9.21502 4.70975 7.93663ZM3.43991 7.93663C3.43991 9.90608 5.05982 11.5025 7.0582 11.5025C9.05658 11.5025 10.6765 9.90608 10.6765 7.93663C10.6765 5.96719 9.05658 4.37074 7.0582 4.37074C5.05982 4.37074 3.43991 5.96719 3.43991 7.93663ZM9.97414 4.22935C9.97408 4.39417 10.0236 4.55531 10.1165 4.69239C10.2093 4.82946 10.3413 4.93633 10.4958 4.99946C10.6503 5.06259 10.8203 5.07916 10.9844 5.04707C11.1484 5.01498 11.2991 4.93568 11.4174 4.81918C11.5357 4.70268 11.6163 4.55423 11.649 4.39259C11.6817 4.23095 11.665 4.06339 11.6011 3.91109C11.5371 3.7588 11.4288 3.6286 11.2898 3.53698C11.1508 3.44536 10.9873 3.39642 10.8201 3.39635H10.8197C10.5955 3.39646 10.3806 3.48424 10.222 3.64043C10.0635 3.79661 9.97434 4.00843 9.97414 4.22935ZM4.21142 13.5892C3.52442 13.5584 3.15101 13.4456 2.90286 13.3504C2.57387 13.2241 2.33914 13.0738 2.09235 12.8309C1.84555 12.588 1.69278 12.3569 1.56527 12.0327C1.46854 11.7882 1.3541 11.4201 1.32287 10.7431C1.28871 10.0111 1.28189 9.79119 1.28189 7.93669C1.28189 6.08219 1.28927 5.86291 1.32287 5.1303C1.35416 4.45324 1.46944 4.08585 1.56527 3.84069C1.69335 3.51647 1.84589 3.28513 2.09235 3.04191C2.3388 2.79869 2.57331 2.64813 2.90286 2.52247C3.1509 2.42713 3.52442 2.31435 4.21142 2.28358C4.95417 2.24991 5.17729 2.24319 7.0582 2.24319C8.9391 2.24319 9.16244 2.25047 9.90582 2.28358C10.5928 2.31441 10.9656 2.42802 11.2144 2.52247C11.5434 2.64813 11.7781 2.79902 12.0249 3.04191C12.2717 3.2848 12.4239 3.51647 12.552 3.84069C12.6487 4.08513 12.7631 4.45324 12.7944 5.1303C12.8285 5.86291 12.8354 6.08219 12.8354 7.93669C12.8354 9.79119 12.8285 10.0105 12.7944 10.7431C12.7631 11.4201 12.6481 11.7881 12.552 12.0327C12.4239 12.3569 12.2714 12.5882 12.0249 12.8309C11.7784 13.0736 11.5434 13.2241 11.2144 13.3504C10.9663 13.4457 10.5928 13.5585 9.90582 13.5892C9.16306 13.6229 8.93994 13.6296 7.0582 13.6296C5.17645 13.6296 4.95395 13.6229 4.21142 13.5892ZM4.15307 1.03424C3.40294 1.06791 2.89035 1.18513 2.4427 1.3568C1.9791 1.53408 1.58663 1.77191 1.19446 2.1578C0.802277 2.54369 0.56157 2.93108 0.381687 3.38797C0.207498 3.82941 0.0885535 4.3343 0.0543922 5.07358C0.0196672 5.81402 0.0117188 6.05074 0.0117188 7.93663C0.0117188 9.82252 0.0196672 10.0592 0.0543922 10.7997C0.0885535 11.539 0.207498 12.0439 0.381687 12.4853C0.56157 12.9419 0.802334 13.3297 1.19446 13.7155C1.58658 14.1012 1.9791 14.3387 2.4427 14.5165C2.89119 14.6881 3.40294 14.8054 4.15307 14.839C4.90479 14.8727 5.1446 14.8811 7.0582 14.8811C8.9718 14.8811 9.212 14.8732 9.96332 14.839C10.7135 14.8054 11.2258 14.6881 11.6737 14.5165C12.137 14.3387 12.5298 14.1014 12.9219 13.7155C13.3141 13.3296 13.5543 12.9419 13.7347 12.4853C13.9089 12.0439 14.0284 11.539 14.062 10.7997C14.0962 10.0587 14.1041 9.82252 14.1041 7.93663C14.1041 6.05074 14.0962 5.81402 14.062 5.07358C14.0278 4.33424 13.9089 3.82913 13.7347 3.38797C13.5543 2.93135 13.3135 2.5443 12.9219 2.1578C12.5304 1.7713 12.137 1.53408 11.6743 1.3568C11.2258 1.18513 10.7135 1.06735 9.96388 1.03424C9.21256 1.00058 8.97236 0.992188 7.05876 0.992188C5.14516 0.992188 4.90479 1.00002 4.15307 1.03424Z"
                                    fill="currentColor" />
                            </svg>

                        </a>
                        <a href="javascript:;"
                            class="w-9 h-9 rounded-full bg-gray-700 flex justify-center items-center hover:bg-indigo-600">
                            <svg class="w-[1rem] h-[1rem] text-white" viewBox="0 0 13 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.8794 11.5527V3.86835H0.318893V11.5527H2.87967H2.8794ZM1.59968 2.81936C2.4924 2.81936 3.04817 2.2293 3.04817 1.49188C3.03146 0.737661 2.4924 0.164062 1.61666 0.164062C0.74032 0.164062 0.167969 0.737661 0.167969 1.49181C0.167969 2.22923 0.723543 2.8193 1.5829 2.8193H1.59948L1.59968 2.81936ZM4.29668 11.5527H6.85698V7.26187C6.85698 7.03251 6.87369 6.80255 6.94134 6.63873C7.12635 6.17968 7.54764 5.70449 8.25514 5.70449C9.18141 5.70449 9.55217 6.4091 9.55217 7.44222V11.5527H12.1124V7.14672C12.1124 4.78652 10.8494 3.68819 9.16483 3.68819C7.78372 3.68819 7.17715 4.45822 6.84014 4.98267H6.85718V3.86862H4.29681C4.33023 4.5895 4.29661 11.553 4.29661 11.553L4.29668 11.5527Z"
                                    fill="currentColor" />
                            </svg>

                        </a>
                        <a href="javascript:;"
                            class="w-9 h-9 rounded-full bg-gray-700 flex justify-center items-center hover:bg-indigo-600">
                            <svg class="w-[1.25rem] h-[0.875rem] text-white" viewBox="0 0 16 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M13.9346 1.13529C14.5684 1.30645 15.0665 1.80588 15.2349 2.43896C15.5413 3.58788 15.5413 5.98654 15.5413 5.98654C15.5413 5.98654 15.5413 8.3852 15.2349 9.53412C15.0642 10.1695 14.5661 10.669 13.9346 10.8378C12.7886 11.1449 8.19058 11.1449 8.19058 11.1449C8.19058 11.1449 3.59491 11.1449 2.44657 10.8378C1.81277 10.6666 1.31461 10.1672 1.14622 9.53412C0.839844 8.3852 0.839844 5.98654 0.839844 5.98654C0.839844 5.98654 0.839844 3.58788 1.14622 2.43896C1.31695 1.80353 1.81511 1.30411 2.44657 1.13529C3.59491 0.828125 8.19058 0.828125 8.19058 0.828125C8.19058 0.828125 12.7886 0.828125 13.9346 1.13529ZM10.541 5.98654L6.72178 8.19762V3.77545L10.541 5.98654Z"
                                    fill="currentColor" />
                            </svg>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div id="drawer-left-example"
        class="fixed left-0 top-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80 sm:w-96"
        tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
        <h2 id="drawer-label" class="text-gray-900 text-lg font-semibold leading-7 mb-1">
            <button class="btn btn-primary bg-[#C19362]   mx-auto px-6 py-2 text-white-900 font-semibold">
                Identificarse
            </button>
            <span class="block px-6 py-1 text-gray-600 text-xs">
                ¿Eres un cliente nuevo? <a href="" class="text-blue-500 hover:underline">Empieza
                    aquí.</a>
            </span>

        </h2>
        <p class="text-gray-500 text-sm font-normal leading-snug mb-4">Menu</p>
        <button type="button" data-drawer-hide="drawer-left-example" aria-controls="drawer-left-example"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
        <div class="py-4 space-y-4">

            <nav class="space-y-4">
                <a href="index.html"
                    class="block text-gray-900 text-base font-medium hover:bg-gray-100 p-2 rounded-lg">Home v1</a>
                <a href="index2.html"
                    class="block text-gray-900 text-base font-medium hover:bg-gray-100 p-2 rounded-lg">Home v2</a>
                <a href="product.html"
                    class="block text-gray-900 text-base font-medium hover:bg-gray-100 p-2 rounded-lg">Product</a>
                <a href="about.html"
                    class="block text-gray-900 text-base font-medium hover:bg-gray-100 p-2 rounded-lg">About</a>

            </nav>

        </div>
        <h2 class="text-gray-900 text-base font-semibold leading-relaxed mb-4">This Week</h2>
        <div class="space-y-4">


        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/js/pagedone.js"></script>
    <script src="{{ asset('js/util.js') }}"></script>

</body>

</html>
