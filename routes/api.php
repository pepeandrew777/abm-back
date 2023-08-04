<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AplicacionController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  //  return $request->user();
//});
//Parámetros
Route::resource('solicitud',SolicitudController::class, ['only' => ['index']]);
Route::resource('area',AreaController::class, ['only' => ['index']]);
Route::resource('aplicacion',AplicacionController::class);
Route::resource('sucursal',SucursalController::class);
Route::resource('usuario',UsuarioController::class);
Route::get('reporte-abm/{id?}', [ReporteController::class, 'index']);


