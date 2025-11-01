@extends('layouts.app')
@section('content')
    @vite('resources/css/app.css')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
                            <select required id="selectDestino" data-url="{{ route('planificar.seleccionarRuta') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-500">
                                <option selected disabled>Selecciona un destino</option>
                                <option value="ocotal">Ocotal</option>
                                <option value="managua">Managua</option>
                                <option value="esteli">Esteli</option>
                                <option value="san jose de cusmapa">Cusmapa</option>
                                <option value="las sabanas">Las sabanas</option>
                                <option value="el espino">El espino</option>


                            </select>
                        </div>

                        <div class="grid gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Día</label>
                                <div class="relative">
                                    <input required type="date" id="inputDia" placeholder="mm/dd/yyyy"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona una ruta</label>
                            <select required id="selectRuta"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-gray-500">
                                <option>Selecciona una ruta</option>
                            </select>
                        </div>

                        <!-- aqui tiene que cargar el precio dependiendo de la ruta-->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio</label>
                            <input readonly type="text" id="inputPrecio" value=""
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-green-500 text-white font-semibold text-lg px-8 py-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 w-full max-w-sm">
                            Seleccionar
                        </button>
                    </form>
                </div>

                <!-- All Planned Trips -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-900">Todos tus Viajes</h2>

                    <div class="space-y-4">
                        <!-- Trip 1 -->
                        <div id="contenedorPlanificaciones"
                            class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                            <p>Cargando planificaciones...</p>




                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar -->
        <div id="modalEditar"
            class="fixed inset-0 backdrop-blur-sm bg-white/30 flex items-center justify-center hidden z-50 transition-all">

            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4 border border-gray-200">
                <!-- Encabezado -->
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-semibold text-gray-800">Editar Plan</h3>
                    <button id="cerrarModal" class="text-gray-500 hover:text-gray-700 transition-colors duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <form id="formEditar" class="space-y-4">
                    <input type="hidden" id="editarId" name="id">



                    <!-- Destino -->
                    <div>
                        <label for="editarDestino" class="block text-sm font-medium text-gray-700">Destino</label>
                        <input type="text" id="editarDestino" name="destino" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Hora -->
                    <div>
                        <label for="editarHora" class="block text-sm font-medium text-gray-700">Hora</label>
                        <input type="time" id="editarHora" name="hora" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Precio -->
                    <div>
                        <label for="editarPrecio" class="block text-sm font-medium text-gray-700">Precio (C$)</label>
                        <input type="number" id="editarPrecio" name="precio" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelarEditar" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md 
                                            hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md 
                                            hover:bg-blue-700 transition-colors">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loader -->
        <div id="loader"
            class="fixed inset-0 flex items-center justify-center bg-white/40 backdrop-blur-md hidden z-[1000]">
            <div
                class="w-14 h-14 border-4 border-blue-500 border-t-transparent rounded-full animate-spin shadow-md shadow-blue-200">
            </div>
        </div>





        @vite(entrypoints: 'resources/js/app.js');


    </body>

    </html>

@endsection