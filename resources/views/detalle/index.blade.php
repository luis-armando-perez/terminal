@extends('layouts.app')

@section('content')
  @vite('resources/css/app.css')



  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
  </head>

  <body id="body"
    class="h-screen bg-gradient-to-br from-blue-200 via-teal-100 to-green-200 flex items-center justify-center">

    <div class="relative w-11/12 h-[90vh] rounded-3xl overflow-hidden shadow-2xl border border-white/40 backdrop-blur-lg">
      @if ($ruta)

        <div class="absolute top-4 left-1/2 transform -translate-x-1/2 z-30 animate-fadeIn">
          <div
            class="backdrop-blur-md bg-white/70 shadow-lg rounded-xl px-4 py-2.5 border border-white/30 flex flex-col items-center text-center w-56">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">
              Ruta
            </div>
            <div class="flex flex-col items-center">
              <div class="text-sm font-semibold text-blue-700">
                {{ $ruta->origen }}
              </div>
              <div class="my-1">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                </svg>
              </div>
              <div class="text-sm font-semibold text-green-700">
                {{ $ruta->destino }}
              </div>
            </div>
          </div>
        </div>


      @endif
      <!-- Mapa -->
      <div id="mapa" class="absolute inset-0 z-0"></div>

      <!-- Gradiente oscuro sutil arriba para legibilidad -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent z-10 pointer-events-none"></div>

      <!-- Controles flotantes -->
      <div class="absolute bottom-6 left-6 right-6 flex flex-wrap justify-between items-end gap-4 z-20">

        <!-- Panel de llegada -->
        @if ($ruta)
          <div
            class="backdrop-blur-md bg-white/60 border border-white/40 shadow-lg rounded-2xl p-4 min-w-[220px] hover:bg-white/70 transition-all duration-300">
            <div class="text-xs text-gray-700 uppercase tracking-wide mb-1">Hora de llegada</div>
            <div class="text-4xl font-bold text-gray-900 mb-2">{{ $ruta->llegada }}</div>
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
              <span class="text-sm text-green-700 font-semibold">A tiempo</span>
            </div>
          </div>

        @endif

        @if ($weatherData)
          <div
            class="backdrop-blur-md bg-white/60 border border-white/40 shadow-lg rounded-2xl p-4 flex items-center gap-4 hover:bg-white/70 transition-all duration-300">
            <div class="bg-blue-600 w-14 h-14 rounded-full flex items-center justify-center shadow-md">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
              </svg>
            </div>
            <div>
              <div class="font-bold text-gray-900 text-sm">
                {{ $weatherData['current']['temp_c'] }}°C
                <img src="https:{{ $weatherData['current']['condition']['icon'] }}" alt="icon" class="w-8 h-8">
              </div>
              <div class="text-xs text-gray-700">{{ $weatherData['current']['condition']['text'] }}</div>
            </div>
            <button class="ml-2 text-gray-500 hover:text-gray-700 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
              </svg>
            </button>
          </div>

        @endif

      </div>
    </div>

    <script>
      var lat_origen = {{ $ruta->latitud_origen }};
      var lon_origen = {{ $ruta->longitud_origen }};
      var lat_destino = {{ $ruta->latitud_destino }};
      var lon_destino = {{ $ruta->longitud_destino }};

      var mapa = L.map("mapa", {
        zoomControl: false,
        preferCanvas: true,
        fadeAnimation: false,
        zoomAnimation: false
      }).setView([lat_origen, lon_origen], 13);

      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "© OpenStreetMap contributors",
      }).addTo(mapa);

      L.Routing.control({
        waypoints: [
          L.latLng(lat_origen, lon_origen),
          L.latLng(lat_destino, lon_destino)
        ],
        routeWhileDragging: false,
        draggableWaypoints: false,
        addWaypoints: false
      }).addTo(mapa);
    </script>
  </body>


  </html>
  @vite('resources/js/app.js')

@endsection