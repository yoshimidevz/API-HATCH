<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EscotilhaController;
use App\Http\Controllers\AuthController;
use App\Services\ApiResponse;

Route::get('/status', function () {
    return ApiResponse::success('API is running');
})->middleware('auth:sanctum');

Route::get('/escotilhas', [EscotilhaController::class, 'listarEscotilha'])->middleware('auth:sanctum', 'ability:clients:list');
Route::post('/escotilhas', [EscotilhaController::class, 'inserirEscotilha'])->middleware('auth:sanctum');
Route::delete('/escotilhas/{id}', [EscotilhaController::class, 'deletarEscotilha'])->middleware('auth:sanctum');
Route::put('/escotilhas/{id}', [EscotilhaController::class, 'atualizarEscotilha'])->middleware('auth:sanctum');
Route::get('/escotilhas/{id}', [EscotilhaController::class, 'obterEscotilhaPorId'])->middleware('auth:sanctum', 'ability:clients:view');

Route::get('/alertas', [App\Http\Controllers\AlertaController::class, 'listarAlertas']);
Route::get('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'obterAlertaPorId']);
Route::post('/alertas', [App\Http\Controllers\AlertaController::class, 'inserirAlerta']);
Route::delete('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'deletarAlerta']);
Route::put('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'atualizarAlerta']);

// Route::apiResource('hatch', HatchController::class);

Route::post('/login', AuthController::class . '@login');
Route::post('/logout', AuthController::class . '@logout')->middleware('auth:sanctum');

