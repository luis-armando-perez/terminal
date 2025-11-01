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

    public function mostrarPlan(Request $request)
    {
        $planes = DB::table('planificar')
            ->join('rutas', 'planificar.ruta_id', '=', 'rutas.id')
            ->select(
                'planificar.id',
                'planificar.destino',
                'planificar.dia',
                'rutas.salida',
                'rutas.tipo',
                'rutas.precio'
            )
            ->orderBy('planificar.id', 'desc')
            ->get();

        return response()->json($planes);
    }


    public function eliminar($id)
    {
        try {
            DB::table('planificar')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Plan eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el plan.']);
        }
    }

    public function actualizar(Request $request, $id)
    {
        try {
            DB::table('planificar')
                ->where('id', $id)
                ->update([
                    'destino' => $request->destino,
                    'hora' => $request->hora,
                    'precio' => $request->precio,

                ]);

            return response()->json(['success' => true, 'message' => 'Plan actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar el plan.']);
        }
    }

    public function guardar(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'ruta_id' => 'required|integer',
            'destino' => 'required|string|max:255',
            'dia' => 'required|date',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            // Para Supabase, usamos el mismo código de Laravel
            // Supabase es PostgreSQL compatible

            DB::table('planificar')->insert([
                'ruta_id' => $validated['ruta_id'],
                'destino' => $validated['destino'],
                'dia' => $validated['dia'], // Nuevo campo
                'precio' => $validated['precio'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Viaje planificado con éxito!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al guardar planificación: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar en la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }




    public function index()
    {
        return view('planificar.planificar');
    }
}
