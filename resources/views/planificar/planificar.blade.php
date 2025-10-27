@extends('layouts.app')
@section('content')
    @vite('resources/css/app.css')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>PLanificar Viaje</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body class="bg-gray-100 p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-8 text-gray-900">Planifica Tu Proximo Viaje</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Plan a New Trip Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-900">Planifica Tu viaje</h2>

                    <form id="formPlanificar" class="space-y-4">
                        <!-- Bus Station -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Origen</label>
                            <input type="text" value="Somoto" readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-500 bg-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Destino</label>
                            <select id="selectDestino"  data-url="{{ route('planificar.seleccionarRuta') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-500">
                                <option selected disabled>Selecciona un destino</option>
                                <option value="ocotal">Ocotal</option>
                                <option value="managua">Managua</option>
                                <option value="esteli">Estali</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Día</label>
                                <div class="relative">
                                    <input type="date" id="inputDia" placeholder="mm/dd/yyyy"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                                <div class="relative">
                                    <input type="time" id="inputHora" placeholder="--:--"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona una ruta</label>
                            <select id="selectRuta"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-gray-500">
                                <option selected disabled>Selecciona una ruta</option>
                            </select>
                        </div>

                        <!-- aqui tiene que cargar el precio dependiendo de la ruta-->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio</label>
                            <input type="text" id="inputPrecio" value=""
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Submit Button -->
                        <button  type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition-colors">
                            Seleccionar
                        </button>
                    </form>
                </div>

                <!-- All Planned Trips -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-900">Todos tus Viajes</h2>

                    <div class="space-y-4">
                        <!-- Trip 1 -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">Somoto</h3>
                                    <p class="text-sm text-gray-600 mb-1">Aqui va la fecha y hora</p>
                                    <p class="text-sm text-gray-500">destino y precio</p>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <button class="text-gray-400 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-red-600 transition-colors">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @vite('resources/js/app.js');


    </body>

    </html>

@endsection