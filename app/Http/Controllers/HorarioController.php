<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudad;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HorarioController extends Controller
{
    public function filtrarRutas(Request $request)
    {
        $destino = $request->input('ciudad');

        $query = DB::table('rutas')
            ->select('id', 'destino', 'tipo', 'salida', 'precio');

        if ($destino) {
            $query->where('destino', $destino);
        }

        $rutas = $query
            ->orderBy('salida', 'asc')
            ->get()
            ->map(function ($ruta) {
                $ruta->salida_formato = Carbon::parse($ruta->salida, 'America/Managua')->format('g:i A');
                return (array) $ruta;
            });

        return response()->json($rutas);
    }

    public function index()
    {
        $apiKey = config('services.weatherapi.key');

        $ciudades = Ciudad::all();
        $response = [];

        foreach ($ciudades as $ciudad) {
            $climaCacheKey = 'clima_' . $ciudad->id;

            $climaData = Cache::remember($climaCacheKey, 900, function () use ($apiKey, $ciudad) {
                $res = Http::withoutVerifying()->get('https://api.weatherapi.com/v1/current.json', [
                    'key' => $apiKey,
                    'q' => "{$ciudad->latitud},{$ciudad->longitud}",
                    'lang' => 'es'
                ]);

                if ($res->successful()) {
                    $data = $res->json();
                    return [
                        'nombre' => $ciudad->nombre_ciudad,
                        'temperatura' => $data['current']['temp_c'],
                        'icon' => $data['current']['condition']['icon'],
                    ];
                }

                return null;
            });

            if ($climaData) {
                $response[] = $climaData;
            }
        }

        return view('horarios.horarios', compact('ciudades', 'response'));
    }
}