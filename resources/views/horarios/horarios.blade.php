@extends('layouts.app')

@section('content')
  @vite('resources/css/app.css')

  <div class="max-w-7xl mx-auto">
    <!-- Loader -->

    <div class="max-w-7xl mx-auto">
      <!-- Filter Section -->
      <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-wrap items-center gap-4">
          <!-- Filter Dropdown -->
          <div class="flex items-center gap-3 flex-1 min-w-[300px]">
            <label for="ciudad" class="text-gray-700 text-sm font-medium whitespace-nowrap">
              Filtrar por ciudad:
            </label>
            <div class="relative flex-1">
              <select id="ciudad"
                class="w-full appearance-none bg-white border border-gray-300 rounded-md px-4 py-2.5 pr-10 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Todas las ciudades</option>
                <option value="managua">Managua</option>
                <option value="ocotal">Ocotal</option>
                <option value="esteli">Esteli</option>
                <option value="san jose de cusmapa">San jose de cusmapa</option>
              </select>

            </div>
          </div>


          <!-- Weather Cards -->
          <div class="flex gap-3 ml-auto">

            @foreach ($ciudades as $ciudad)
              @php
                $climaCiudad = collect($response)->firstWhere('nombre', $ciudad->nombre_ciudad);

              @endphp
              <div
                class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg px-4 py-3 text-center min-w-[90px]">
                <div class="text-yellow-500 mb-1">

                </div>
                <div class="text-xs text-gray-600 font-medium mb-0.5">{{ $ciudad->nombre_ciudad }}</div>
                @if($climaCiudad)
                  <!-- Icono del clima -->
                  <img src="{{ $climaCiudad['icon'] }}"  class="w-12 h-12 mb-1">

                  <!-- Temperatura -->
                  <p class="text-xl font-bold text-gray-900 mb-1">{{ $climaCiudad['temperatura'] }}°C</p>
                @else
                  <p class="text-xs text-gray-600">No hay datos de clima</p>
                @endif
              </div>

            @endforeach

          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-800">Resultados</h2>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">

          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Destino</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Salida</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo de
                  Autobus</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
              </tr>
            </thead>
            <tbody id="tablaRutas" class="divide-y divide-gray-200">

              <tr class="hover:bg-gray-50 transition-colors">
                <td colspan="6" class="text-center text-gray-500 py-4">
                  Selecciona una ciudad para ver las rutas
                </td>
              </tr>

            </tbody>
          </table>
        </div>


      </div>
    </div>
  </div>

  @vite('resources/js/app.js')

@endsection