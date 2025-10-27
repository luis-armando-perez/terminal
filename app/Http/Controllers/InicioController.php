<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InicioController extends Controller
{
    public function index(Request $request)
    {
        $hora_actual = Carbon::now('America/Managua');
        $hora_inicio = $hora_actual->copy()->subMinutes(30);
        $hora_fin = $hora_actual->copy()->addMinutes(30);

        // Clave de cache que cambia cada 30 minutos
        $hora_cache = floor($hora_actual->minute / 30) * 30;
        $clave_cache = 'rutas_base_' . $hora_actual->format('H') . '_' . $hora_cache;

        // Cache de 30 minutos
        $rutas = Cache::remember($clave_cache, now()->addMinutes(30), function () use ($hora_inicio, $hora_fin) {
            return DB::table('rutas')
                ->select(
                    'id',
                    'origen',
                    'destino',
                    'transporte',
                    'tipo',
                    'salida',
                    'llegada',
                    'precio',
                )
                ->where('salida', '>=', $hora_inicio)  // comparación de datetime
                ->where('salida', '<=', $hora_fin)
                ->orderBy('salida', 'asc')
                ->get()
                ->map(function ($ruta) {
                    // Formato de hora en zona horaria correcta
                    $ruta->salida_formato = Carbon::parse($ruta->salida, 'America/Managua')->format('g:i A');
                    $ruta->llegada_formato = Carbon::parse($ruta->llegada, 'America/Managua')->format('g:i A');
                    return (array) $ruta;
                })
                ->toArray();
        });

        $mensaje = null;

        if (empty($rutas)) {
            // Buscar próxima ruta disponible
            $proxima_ruta = DB::table('rutas')
                ->select('salida')
                ->where('salida', '>', $hora_actual)
                ->orderBy('salida', 'asc')
                ->first();

            if (!$proxima_ruta) {
                $proxima_ruta = DB::table('rutas')
                    ->select('salida')
                    ->orderBy('salida', 'asc')
                    ->first();
            }

            $mensaje = $proxima_ruta
                ? "No hay rutas disponibles ahora. La próxima sale a las " . Carbon::parse($proxima_ruta->salida, 'America/Managua')->format('g:i A') . "."
                : "No hay rutas disponibles por el momento.";
        }

        return view('inicio.index', compact('rutas', 'mensaje'));
    }
}
