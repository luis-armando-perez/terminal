<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\NotificacionPlanificacion;

class PlanificarViajeController extends Controller
{

    public function mostrarPlan(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

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
            ->where('planificar.tenant_id', $tenantId) // ⚡ solo planes de este tenant
            ->orderBy('planificar.id', 'desc')
            ->get();

        return response()->json($planes);
    }

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



    public function eliminar($id)
    {
        try {
            DB::table('planificar')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Plan eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el plan.']);
        }
    }


    /*    public function guardar(Request $request)
        {
            $validated = $request->validate([
                'ruta_id' => 'required|integer',
                'destino' => 'required|string|max:255',
                'dia' => 'required|date',
                'precio' => 'required|numeric|min:0',
            ]);

            try {
                // Obtener tenant_id del usuario logueado
                $tenantId = auth()->user()->tenant_id;

                DB::table('planificar')->insert([
                    'ruta_id' => $validated['ruta_id'],
                    'destino' => $validated['destino'],
                    'dia' => $validated['dia'],
                    'precio' => $validated['precio'],
                    'tenant_id' => $tenantId, // ⚡ Asociamos el plan al tenant
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
    */
    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'ruta_id' => 'required|integer',
            'destino' => 'required|string|max:255',
            'dia' => 'required|date',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            $tenantId = auth()->user()->tenant_id;

            // Insertar y obtener el ID recién creado
            $id = DB::table('planificar')->insertGetId([
                'ruta_id' => $validated['ruta_id'],
                'destino' => $validated['destino'],
                'dia' => $validated['dia'],
                'precio' => $validated['precio'],
                'tenant_id' => $tenantId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Traer solo la planificación recién creada con join a rutas
            $planificaciones = DB::table('planificar')
                ->join('rutas', 'planificar.ruta_id', '=', 'rutas.id')
                ->select(
                    'planificar.dia',
                    'planificar.destino',
                    'rutas.salida as hora'
                )
                ->where('planificar.id', $id)
                ->get();

            // Enviar correo al usuario

            // Enviar correo al usuario logueado
            $usuario = auth()->user(); // Obtenemos el usuario completo
            $correo = $usuario->email;
            Mail::to($correo)->send(new NotificacionPlanificacion($planificaciones, $usuario));

            return response()->json([
                'success' => true,
                'message' => 'Viaje planificado y notificación enviada con éxito!'
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
