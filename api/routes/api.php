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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//TOKEN
Route::get('token','BienvenidoController@getToken');
// CRUD DE EMPRESA
Route::get('empresas', 'empresaController@inicio')->middleware('token');
Route::get('empresaMostrar', 'empresaController@mostrarEmpresa');
Route::get('tablaEmpresa', 'empresaController@tablaEmpresa');
Route::get('empresas/{id}', 'empresaController@mostrar');
Route::post('empresas', 'empresaController@registrar');
Route::post('empresas/{id}', 'empresaController@actualizar');
Route::delete('empresa/{id}', 'empresaController@eliminar');
Route::patch('empresa/{id}', 'empresaController@cambiarEstado');
// *********************************************************