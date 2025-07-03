<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EscotilhaController;

Route::get('/escotilhas', [EscotilhaController::class, 'listarEscotilha']);
Route::post('/escotilhas', [EscotilhaController::class, 'inserirEscotilha']);
Route::delete('/escotilhas/{id}', [EscotilhaController::class, 'deletarEscotilha']);
Route::put('/escotilhas/{id}', [EscotilhaController::class, 'atualizarEscotilha']);

Route::get('/alertas', [App\Http\Controllers\AlertaController::class, 'listarAlertas']);
Route::get('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'obterAlertaPorId']);
Route::post('/alertas', [App\Http\Controllers\AlertaController::class, 'inserirAlerta']);
Route::delete('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'deletarAlerta']);
Route::put('/alertas/{id}', [App\Http\Controllers\AlertaController::class, 'atualizarAlerta']);
