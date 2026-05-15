<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\OrgaoPublicoController;
use App\Http\Controllers\DespesaPEController;

Route::prefix('v1')->group(function () {
    // Rota inicial da API
    Route::get('/', function () {
        return response()->json([
            'status' => 'API de Gastos Públicos ativa',
            'versao' => 'v1'
        ]);
    })->name('api.status');

    // Recurso RESTful "gastos"
    Route::get('/gastos', [GastoController::class, 'index'])->name('gastos.index');
    Route::post('/gastos', [GastoController::class, 'store'])->name('gastos.store');

    // Rotas adicionais de gastos
    Route::get('/gastos/educacao', [GastoController::class, 'educacao'])->name('gastos.educacao');
    Route::get('/gastos/ranking', [GastoController::class, 'ranking'])->name('gastos.ranking');
    Route::post('/gastos/importar', [GastoController::class, 'importar'])->name('gastos.importar');

    // Rotas dinâmicas (devem vir depois das fixas)
    Route::get('/gastos/{gasto}', [GastoController::class, 'show'])->name('gastos.show');
    Route::put('/gastos/{gasto}', [GastoController::class, 'update'])->name('gastos.update');
    Route::patch('/gastos/{gasto}', [GastoController::class, 'updatePartial'])->name('gastos.updatePartial');
    Route::delete('/gastos/{gasto}', [GastoController::class, 'destroy'])->name('gastos.destroy');

    // Recurso RESTful "órgãos públicos"
    Route::get('/orgaos', [OrgaoPublicoController::class, 'index'])->name('orgaos.index');
    Route::post('/orgaos', [OrgaoPublicoController::class, 'store'])->name('orgaos.store');
    Route::get('/orgaos/{orgao}', [OrgaoPublicoController::class, 'show'])->name('orgaos.show');
    Route::put('/orgaos/{orgao}', [OrgaoPublicoController::class, 'update'])->name('orgaos.update');
    Route::delete('/orgaos/{orgao}', [OrgaoPublicoController::class, 'destroy'])->name('orgaos.destroy');

    // Recurso "despesas de PE"
    Route::post('/pe/despesas/importar', [DespesaPEController::class, 'importar'])->name('pe.despesas.importar');
    Route::get('/pe/despesas', [DespesaPEController::class, 'index'])->name('pe.despesas.index');
    Route::get('/pe/despesas/ranking', [DespesaPEController::class, 'ranking'])->name('pe.despesas.ranking');
});

// Rota protegida por autenticação (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
})->name('user.profile');
