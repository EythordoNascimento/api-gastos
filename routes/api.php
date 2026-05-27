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

    // 🔹 Rota fixa do ranking (deve vir antes das dinâmicas)
    Route::get('/gastos/ranking', [GastoController::class, 'ranking'])->name('gastos.ranking');

    // Rotas dinâmicas (devem vir depois das fixas)
    Route::get('/gastos/{gasto}', [GastoController::class, 'show'])->name('gastos.show');
    Route::put('/gastos/{gasto}', [GastoController::class, 'update'])->name('gastos.update');
    Route::patch('/gastos/{gasto}', [GastoController::class, 'updatePartial'])->name('gastos.updatePartial');
    Route::delete('/gastos/{gasto}', [GastoController::class, 'destroy'])->name('gastos.destroy');

    // Recurso RESTful "órgãos públicos"
    Route::get('/orgaos', [OrgaoPublicoController::class, 'index'])->name('orgaos.index');
    Route::post('/orgaos', [OrgaoPublicoController::class, 'store'])->name('orgaos.store');

    // Rota fixa deve vir antes das dinâmicas
    Route::get('/orgaos/ranking', [OrgaoPublicoController::class, 'ranking'])->name('orgaos.ranking');

    // Rotas dinâmicas (devem vir depois das fixas)
    Route::get('/orgaos/{orgao}', [OrgaoPublicoController::class, 'show'])->name('orgaos.show');
    Route::put('/orgaos/{orgao}', [OrgaoPublicoController::class, 'update'])->name('orgaos.update');
    Route::delete('/orgaos/{orgao}', [OrgaoPublicoController::class, 'destroy'])->name('orgaos.destroy');

    // Recurso "despesas de PE"
    Route::prefix('pe/despesas')->group(function () {
        Route::post('/importar', [DespesaPEController::class, 'importar'])->name('pe.despesas.importar');
        Route::get('/', [DespesaPEController::class, 'index'])->name('pe.despesas.index');
        Route::get('/ranking', [DespesaPEController::class, 'ranking'])->name('pe.despesas.ranking');
        Route::get('/filtro', [DespesaPEController::class, 'filtro'])->name('pe.despesas.filtro');
        Route::get('/comparativo', [DespesaPEController::class, 'comparativo'])->name('pe.despesas.comparativo');
        Route::get('/indicadores', [DespesaPEController::class, 'indicadores'])->name('pe.despesas.indicadores');
    });
});

// Rota protegida por autenticação (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
})->name('user.profile');
