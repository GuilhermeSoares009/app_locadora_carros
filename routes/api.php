<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CarroController,
    ClienteController,
    LocacaoController,
    MarcaController,
    ModeloController
};

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


Route::apiResource('cliente', ClienteController::class);
Route::apiResource('Carro', CarroController::class);
Route::apiResource('Locacao', LocacaoController::class);
Route::apiResource('Marca', MarcaController::class);
Route::apiResource('Modelo', ModeloController::class);