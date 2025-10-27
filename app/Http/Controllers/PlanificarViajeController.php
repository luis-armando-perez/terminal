<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanificarViajeController extends Controller
{
    /* public function seleccionarRuta(Request $request)
     {
         $destino = $request->query('destino'); // captura ?destino=algo

         $query = DB::table('rutas')
             ->select('id', 'destino', 'salida', 'precio');

         if ($destino) {
             $query->where('destino', $destino);
         }

         $rutas = $query->get();

         return response()->json($rutas);
     }*/
    public function seleccionarRuta(Request $request)
    {
        $destino = $request->query('destino'); // ciudad
        $horaSeleccionada = $request->query('hora'); // hora elegida por el usuario, formato HH:MM

        $query = DB::table('rutas')
            ->select('id', 'destino', 'salida', 'precio', 'tipo');

        if ($destino) {
            $query->where('destino', $destino);
        }

        if ($horaSeleccionada) {
            // Calculamos hora inicio y hora fin (1 hora de lapso)
            $horaInicio = $horaSeleccionada . ':00';
            $horaFin = date('H:i:s', strtotime($horaSeleccionada . ' +1 hour'));

            $query->whereTime('salida', '>=', $horaInicio)
                ->whereTime('salida', '<', $horaFin);
        }

        $rutas = $query->orderBy('salida', 'asc')->get();

        return response()->json($rutas);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'ruta_id' => 'required|integer',
            'destino' => 'required|string',
            'hora' => 'required',
            'precio' => 'required|numeric',
        ]);

        DB::table('planificar')->insert([
            'ruta_id' => $request->ruta_id,
            'destino' => $request->destino,
            'hora' => $request->hora,
            'precio' => $request->precio,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Viaje planificado con éxito!']);
    }




    public function index()
    {
        return view('planificar.planificar');
    }
}
