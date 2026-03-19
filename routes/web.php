<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status'   => 'API de Gastos Públicos funcionando',
        'versao'   => 'v1',
        'tipo'     => 'backend',
        'mensagem' => 'Bem-vindo! Esta API fornece dados de despesas governamentais.'
    ]);
});