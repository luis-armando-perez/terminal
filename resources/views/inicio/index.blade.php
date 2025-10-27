@extends('layouts.app')

@section('content')
    @vite('resources/css/app.css')






    <div class="bg-white rounded-2xl flex flex-wrap justify-start items-start gap-6 shadow-lg w-full p-6 mx-auto">

        @if(isset($mensaje))
            <div class="flex items-center gap-3 bg-gradient-to-r from-yellow-50 to-yellow-100 
                                               border-l-4 border-yellow-500 text-yellow-800 px-4 py-3 rounded-xl mb-5 
                                               shadow-sm transition-all duration-500 hover:shadow-md">
                <!-- Icono de alerta -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 flex-shrink-0 animate-pulse" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <!-- Texto del mensaje -->
                <div class="font-medium text-base">
                    {{ $mensaje }}
                </div>
            </div>
        @endif

        @foreach ($rutas as $ruta)

            <!-- Card individual -->
            <div
                class="bg-gray-50 rounded-2xl shadow-md p-5 
                                                                                flex flex-col justify-between 
                                                                                transition-transform hover:scale-105 hover:shadow-xl duration-300
                                                                                w-[250px] sm:w-[260px] md:w-[280px] lg:w-[300px] xl:w-[310px]">

                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $ruta['transporte'] }}</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $ruta['tipo'] ?? 'tipo no disponible' }}</p>
                    </div>

                    <div class="flex flex-col items-center">
                        <span class="text-base font-bold text-green-600 mb-1">{{ $ruta['precio'] }}C$</span>

                    </div>
                </div>

                <!-- Ruta -->
                <div class="border-t border-b border-gray-200 py-4 mb-4">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div class="w-0.5 h-10 bg-blue-200"></div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Origen</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $ruta['origen'] }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-3 h-3 border-2 border-blue-500 rounded-full bg-white"></div>
                        <div>
                            <p class="text-xs text-gray-500">Destino</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $ruta['destino'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Info de tiempo -->
                <div class="mb-3">
                    <p class="text-xs text-gray-500">Salida / Llegada </p>
                    <p class="text-sm font-bold text-gray-900">
                        {{ $ruta['salida_formato'] }} / {{ $ruta['llegada_formato'] }}
                    </p>
                </div>
                <a href="{{ route('detalle', ['id' => $ruta['id']]) }}">
                    <button
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded-lg transition-colors">
                        Ver Mapa
                    </button>
                </a>

            </div>
        @endforeach




    </div>

    @vite('resources/js/app.js')




@endsection