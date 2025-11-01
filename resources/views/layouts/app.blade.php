<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <!-- Navbar Superior -->
    <header class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between p-4">
            <!-- Logo / Nombre del sitio -->
            <h1 class="text-2xl font-bold text-gray-800">Mi Sitio</h1>

            <!-- Menú links (desktop) -->
            <nav class="hidden lg:flex gap-4">
                <a href="/inicio"
                    class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-md hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">
                    <x-heroicon-o-home class="w-5 h-5 text-gray-500" />
                    <span>Inicio</span>
                </a>
                <a href="/horarios"
                    class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-md hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">
                    <x-heroicon-o-clock class="w-5 h-5 text-gray-500" />
                    <span>Horarios</span>
                </a>
                <a href="/planificar"
                    class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-md hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">
                    <x-heroicon-o-calendar class="w-5 h-5 text-gray-500" />
                    <span>Planificar Viaje</span>
                </a>
                <a href="/login"
                    class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-md hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">
                    <span>Inicia sesion</span>
                </a>
            </nav>

            <!-- Botón hamburguesa móvil -->
            <button id="btnHamburger" class="lg:hidden p-2 rounded-md bg-white shadow hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Menú móvil -->
        <div id="mobileMenu" class="lg:hidden hidden bg-white shadow-md">
            <ul class="flex flex-col">
                <li>
                    <a href="/inicio"
                        class="block px-4 py-3 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">Inicio</a>
                </li>
                <li>
                    <a href="/horarios"
                        class="block px-4 py-3 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">Horarios</a>
                </li>
                <li>
                    <a href="/planificar"
                        class="block px-4 py-3 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">Planificar
                        Viaje</a>
                </li>
                <li>
                    <a href="/login"
                        class="block px-4 py-3 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-all duration-200">Inica sesion</a>
                </li>
            </ul>
        </div>
    </header>

    <main class="pt-20 p-6">
        @yield('content')
    </main>

    <script>
        const btnHamburger = document.getElementById('btnHamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        btnHamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>

</body>