<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DetalleController extends Controller
{
    public function index($id)
    {
        $ruta = DB::table('rutas')
            ->where('id', $id)
            ->select(
                'id',
                'origen',
                'destino',
                'llegada',
                'latitud_origen',
                'longitud_origen',
                'latitud_destino',
                'longitud_destino'
            )
            ->first();
        if (!$ruta) {
            abort(404, 'Ruta no encontrada');

        }

        $apiKey = config('services.weatherapi.key');
        $lat = $ruta->latitud_destino;
        $lon = $ruta->longitud_destino;


        $response = Http::withoutVerifying()->get('https://api.weatherapi.com/v1/current.json', [
            'key' => $apiKey,
            'q' => "$lat,$lon",
            'lang' => 'es'
        ]);


        if ($response->successful()) {
            $weatherData = $response->json();
            // Puedes pasar $weatherData a la vista si es necesario
        } else {
            // Manejar el error de la API si es necesario
        }

        return view('detalle.index', compact('ruta', "weatherData"));

    }



}
