<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\DetalleController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PlanificarViajeController;
use App\Http\Controllers\LoginRegisterController; //solo mostrar la vista
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Auth\LoginController;



Route::get('/', [InicioController::class, 'index']);

Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');

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
//AJAX para mostrar los planes guardados
Route::get('/planificar/listar', [PlanificarViajeController::class, 'mostrarPlan'])->name('planificar.listar');
//ruta para eliminar planes
Route::delete('/planificar/eliminar/{id}', [PlanificarViajeController::class, 'eliminar'])->name('planificar.eliminar');




//login y register mostrar vista
Route::get('/registrarUsuario', [LoginRegisterController::class, 'index']);

//registrar usuario
Route::post('/register', action: [RegisterController::class, 'register'])->name('register');

// Mostrar el formulario de login (opcional si ya tienes la vista)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Procesar el login
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');