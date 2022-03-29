<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Creacion de Cuenta y Loggeo
Route::post('/registrarse', [App\Http\Controllers\UsuarioController::class, 'registrar']);
Route::post('/conectarse', [App\Http\Controllers\UsuarioController::class, 'login']);

//auto
Route::post('/autos', [App\Http\Controllers\AutosController::class, 'index']);
Route::post('/autos/registrar_autos', [App\Http\Controllers\AutosController::class, 'registrar_auto']);
Route::post('/autos/filtros_busqueda', [App\Http\Controllers\AutosController::class, 'filtros_busqueda']);
