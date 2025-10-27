<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

class RutasExtendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = Storage::get('data_extends.json');
        $rutas = json_decode($json, true);

        foreach ($rutas as $ruta) {
            DB::table('rutas')->insert([
                'origen' => $ruta['origen'],
                'destino' => $ruta['destino'],
                'transporte' => $ruta['transporte'],
                'tipo' => $ruta['tipo'],
                'salida' => $this->convertirHora($ruta['salida']),
                'llegada' => $this->convertirHora($ruta['llegada']),
                'precio' => $ruta['precio'],
                'latitud_origen' => $ruta['latitud_origen'] ?? null,
                'longitud_origen' => $ruta['longitud_origen'] ?? null,
                'latitud_destino' => $ruta['latitud_destino'] ?? null,
                'longitud_destino' => $ruta['longitud_destino'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Convierte formato "03: 45 AM" a "03:45:00" para MySQL/Supabase
     */
    private function convertirHora(string $horaTexto): string
    {
        // Paso 1: Eliminar espacios extra y normalizar
        $horaLimpia = preg_replace('/\s*:\s*/', ':', $horaTexto);
        $horaLimpia = preg_replace('/\s+/', ' ', $horaLimpia);
        
        // Paso 2: Separar componentes
        list($horaMinuto, $periodo) = explode(' ', $horaLimpia);
        list($horas, $minutos) = explode(':', $horaMinuto);
        
        // Paso 3: Convertir a formato 24 horas
        $horas = (int)$horas;
        $minutos = (int)$minutos;
        
        if (strtoupper($periodo) === 'PM' && $horas < 12) {
            $horas += 12;
        }
        
        if (strtoupper($periodo) === 'AM' && $horas == 12) {
            $horas = 0;
        }
        
        // Paso 4: Formatear al estándar MySQL TIME
        return sprintf('%02d:%02d:00', $horas, $minutos);
    }
}