<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\DetalleController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PlanificarViajeController;


Route::get('/', [InicioController::class, 'index']);

Route::get('/inicio', [InicioController::class, 'index']);
Route::get('/horarios', [HorarioController::class, 'index']);
//AJAX para filtrar rutas por ciudad
Route::get('/horarios/ajax', [HorarioController::class, 'filtrarRutas'])->name('horarios.ajax');

Route::get('/detalle/{id}', [DetalleController::class, 'index'])->name('detalle');

//ruta para planificar viaje, solo la vista
Route::get('/planificar', [PlanificarViajeController::class, 'index']);

//AJAX para seleccionar ruta
Route::get('/planificar/seleccionarRuta', [PlanificarViajeController::class, 'seleccionarRuta'])->name('planificar.seleccionarRuta');
//ruta para procesar el formulario de planificar viaje
Route::post('/planificar/guardar', [PlanificarViajeController::class, 'guardar'])->name('planificar.guardar');
