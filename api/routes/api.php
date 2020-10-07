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
Route::get('empresaMostrar', 'empresaController@mostrarEmpresa')->middleware('token');
Route::get('tablaEmpresa', 'empresaController@tablaEmpresa')->middleware('token');
Route::get('empresas/{id}', 'empresaController@mostrar')->middleware('token');
Route::post('empresas', 'empresaController@registrar')->middleware('token');
Route::post('empresas/{id}', 'empresaController@actualizar')->middleware('token');
Route::delete('empresa/{id}', 'empresaController@eliminar')->middleware('token');
Route::patch('empresa/{id}', 'empresaController@cambiarEstado')->middleware('token');
// *********************************************************
// CRUD DE SISTEMA DE PENSIONES

Route::get('sistemasdepensiones', 'sistemadepensionController@inicio')->middleware('token');
Route::get('tablaSistemaDePension', 'sistemadepensionController@tablaSistemaDePension')->middleware('token');
Route::get('sistemasdepensiones/{id}', 'sistemadepensionController@mostrar')->middleware('token');
Route::post('sistemasdepensiones', 'sistemadepensionController@registrar')->middleware('token');
Route::post('sistemasdepensiones/{id}', 'sistemadepensionController@actualizar')->middleware('token');
Route::delete('sistemadepension/{id}', 'sistemadepensionController@eliminar')->middleware('token');
Route::patch('sistemadepension/{id}', 'sistemadepensionController@cambiarEstado')->middleware('token');
