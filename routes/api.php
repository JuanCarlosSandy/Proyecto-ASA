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
Route::get('/ropa','EntradaRopaController@index');

Route::post('/salidaProductos/registrar', 'SalidaProductosController@store');
