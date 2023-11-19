<?php

use App\Http\Controllers\RopaController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::put('/donador/actualizar','DonadorController@update');

Route::post('/producto/registrar','EntradaProductoController@store');
Route::get('/producto/mostrar','EntradaProductoController@show');
Route::get('/donador/buscarDonador','DonadorController@buscarDonador');
Route::get('/ropa','RopaController@index');
Route::get('/ropa/obtenerDetalles', 'RopaController@obtenerDetalles');

Route::post('/salidaProductos/registrar', 'SalidaProductosController@store');

Route::post('/salidaRopas/registrar', 'SalidaRopasController@store');
Route::get('/salidaRopas', 'SalidaRopasController@index');
Route::get('/salidaRopas/obtenerCabecera', 'SalidaRopasController@obtenerCabecera');
Route::get('/salidaRopas/obtenerDetalles', 'SalidaRopasController@obtenerDetalles');

Route::get ('/ropa/buscarRopasId','RopaController@buscarRopaId');
Route::get ('/ropa/buscarRopasNombre','RopaController@buscarRopaNombre');
Route::get('/dashboard', 'DashboardController');
Route::get('/ropas/ropasBajoStock', 'RopaController@ropasBajoStock');
Route::get('/donador/selectDonador', 'DonadorController@selectDonador');
Route::get ('/productos/buscarProductosId','ProductoController@buscarProductoId');


