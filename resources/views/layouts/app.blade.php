<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <!-- Botón hamburguesa -->
    <button id="btnHamburger" onclick="toggleSidebar()"
        class="fixed top-4 left-4 z-50 p-2 rounded-md bg-white shadow-md lg:hidden hover:bg-gray-100 transition-colors">
        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 backdrop-blur-sm bg-white/10 z-40 hidden transition-opacity duration-300"
        onclick="toggleSidebar()">
    </div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-white shadow-xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Mi Sitio</h1>
            <!-- Botón cerrar solo en móvil -->
            <button id="close" onclick="toggleSidebar()"
                class="lg:hidden text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                </svg>
            </button>
        </div>

        <nav class="p-4">
            <ul class="space-y-2">
                <li>
                    <a href="/inicio"
                        class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 group">
                        <x-heroicon-o-home class="w-6 h-6 text-gray-500" />

                        <span class="font-medium">Inicio</span>
                    </a>
                </li>

                <li>
                    <a href="/horarios" 
                        class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 group">
                        <x-heroicon-o-clock class="w-6 h-6 text-gray-500" />
                        <span class="font-medium">Horarios</span>
                    </a>
                </li>
                <li>
                    <a href="/planificar"
                        class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 group">
                        <x-heroicon-o-calendar class="w-6 h-6 text-gray-500" />
                        <span class="font-medium">PLanificar Viaje</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200 group">

                        <span class="font-medium">Ayuda</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    @vite('resources/js/app.js')
    <main class="lg:ml-64 p-6 transition-all duration-300">
        @yield('content')
    </main>
</body>