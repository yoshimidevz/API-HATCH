<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EscotilhaController;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\AuthController;
use App\Services\ApiResponse;

Route::get('/status', function () {
    return ApiResponse::success('API is running');
})->middleware('auth:sanctum');


Route::get('/escotilhas', [EscotilhaController::class, 'listarEscotilhas'])->middleware('auth:sanctum','ability:admin');
Route::get('/escotilhas/{id}', [EscotilhaController::class, 'obterPorId'])->middleware('auth:sanctum','ability:clients:list');
Route::post('/escotilhas', [EscotilhaController::class, 'cadastrar'])->middleware('auth:sanctum','ability:admin');
Route::put('/escotilhas/{id}', [EscotilhaController::class, 'atualizar'])->middleware('auth:sanctum','ability:admin');
Route::delete('/escotilhas/{id}', [EscotilhaController::class, 'deletar'])->middleware('auth:sanctum','ability:admin');

Route::get('/sensores', [SensorDataController::class, 'listarSensorData'])->middleware('auth:sanctum','ability:admin');
Route::post('/sensores', [SensorDataController::class, 'inserirSensorData'])->middleware('auth:sanctum','ability:admin');
Route::get('/sensores/{id}', [SensorDataController::class, 'obterSensorDataPorId'])->middleware('auth:sanctum','ability:admin');

Route::get('/alertas', [App\Http\Controllers\AlertaController::class, 'listarAlertas'])->middleware('auth:sanctum','ability:clients:list');
Route::get('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'obterAlertaPorId'])->middleware('auth:sanctum','ability:clients:view');
Route::delete('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'deletarAlerta'])->middleware('auth:sanctum','ability:admin');

Route::post('/login', [AuthController::class, '@login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, '@logout'])->middleware('auth:sanctum');

