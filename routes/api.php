<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GastoController;

Route::prefix('v1')->group(function () {
    // Rota inicial da API
    Route::get('/', function () {
        return response()->json([
            'status' => 'API de Gastos Públicos ativa',
            'versao' => 'v1'
        ]);
    });

    // Recurso RESTful "gastos"
    Route::get('/gastos', [GastoController::class, 'index']);
    Route::post('/gastos', [GastoController::class, 'store']);
    Route::get('/gastos/{id}', [GastoController::class, 'show']);
    Route::put('/gastos/{id}', [GastoController::class, 'update']);   
    Route::patch('/gastos/{id}', [GastoController::class, 'updatePartial']); 
    Route::delete('/gastos/{id}', [GastoController::class, 'destroy']);
});

// Rota protegida por autenticação (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});