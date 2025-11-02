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

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
      font-family: 'Inter', sans-serif;
    }

    .glass-effect {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .leaflet-container {
      border-radius: 1.5rem;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease;
    }

    .leaflet-container:hover {
      transform: scale(1.01);
    }

    .pulse-dot {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.6; transform: scale(1.2); }
    }

    @media (max-width: 640px) {
      .info-panels {
        flex-direction: column;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        align-items: center;
      }
      .info-card { width: 90%; }
    }
  </style>
</head>

<body class="h-screen bg-gradient-to-br from-blue-100 via-teal-50 to-green-100 flex items-center justify-center">

  <div class="relative w-11/12 h-[90vh] rounded-3xl overflow-hidden shadow-2xl border border-white/30 backdrop-blur-lg">

    @if ($ruta)
    <!-- HEADER DE RUTA -->
    <div class="absolute top-4 left-1/2 transform -translate-x-1/2 z-20 w-[90%] sm:w-auto">
      <div class="glass-effect rounded-2xl shadow-xl p-4 text-center sm:text-left">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Ruta</p>
        <div class="flex flex-wrap justify-center sm:justify-start items-center gap-2">
          <span class="text-lg font-bold text-blue-600">{{ $ruta->origen }}</span>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
          <span class="text-lg font-bold text-purple-600">{{ $ruta->destino }}</span>
        </div>
      </div>
    </div>
    @endif

    <!-- MAPA -->
    <div id="mapa" class="w-full h-full"></div>

    <!-- Gradiente sutil -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent z-10 pointer-events-none"></div>

    <!-- PANEL INFERIOR -->
    <div class="absolute bottom-6 left-6 right-6 flex justify-between flex-wrap gap-4 z-20 info-panels">

      @if ($ruta)
      <div class="glass-effect rounded-2xl shadow-2xl p-5 min-w-[260px] info-card">
        <div class="flex items-start justify-between mb-3">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Hora de Llegada</p>
            <h2 class="text-4xl font-bold text-gray-900 tracking-tight">{{ $ruta->llegada }}</h2>
          </div>
          <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-2 rounded-xl">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="flex items-center gap-2 pt-3 border-t border-gray-200">
          <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
          </span>
          <span class="text-sm font-semibold text-green-600">A tiempo</span>
        </div>
      </div>
      @endif

      @if(isset($weatherData['current']))
      <div class="glass-effect rounded-2xl shadow-2xl p-5 min-w-[260px] info-card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Clima Actual</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $weatherData['current']['temp_c'] }}°C</h3>
            <p class="text-sm text-gray-600 font-medium">{{ $weatherData['current']['condition']['text'] }}</p>
          </div>
          <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-3 rounded-xl">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
            </svg>
          </div>
        </div>
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
    }).setView([lat_origen, lon_origen], 13);

    // Nuevo fondo más limpio
    L.tileLayer("https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png", {
      maxZoom: 19,
      attribution: "© OpenStreetMap contributors",
    }).addTo(mapa);

    // Marcadores con iconos
    var origenIcon = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // 🚌 icono de bus de origen
      iconSize: [36, 36],
      iconAnchor: [18, 36],
    });

    var destinoIcon = L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // 🏁 icono de destino, puedes cambiarlo
      iconSize: [36, 36],
      iconAnchor: [18, 36],
    });

    L.marker([lat_origen, lon_origen], { icon: origenIcon }).addTo(mapa);
    L.marker([lat_destino, lon_destino], { icon: destinoIcon }).addTo(mapa);

    // Ruta visual
    L.Routing.control({
      waypoints: [
        L.latLng(lat_origen, lon_origen),
        L.latLng(lat_destino, lon_destino)
      ],
      lineOptions: {
        styles: [{ color: '#3B82F6', opacity: 0.9, weight: 5 }]
      },
      createMarker: () => null,
      routeWhileDragging: false,
      draggableWaypoints: false,
      addWaypoints: false
    }).addTo(mapa);
  </script>
</body>

</html>
@vite('resources/js/app.js')
@endsection
