<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GastoController extends Controller
{
    // Listar todos os gastos
    public function index()
    {
        return response()->json([
            'message' => 'Listando todos os gastos',
            'data' => [
                ['id' => 1, 'descricao' => 'Compra de material', 'valor' => 150.00],
                ['id' => 2, 'descricao' => 'Serviço de manutenção', 'valor' => 300.00],
            ]
        ]);
    }

    // Criar novo gasto
    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Novo gasto criado',
            'data' => $request->all()
        ], 201);
    }

    // Mostrar gasto específico
    public function show($id)
    {
        return response()->json([
            'message' => "Detalhes do gasto {$id}",
            'data' => ['id' => $id, 'descricao' => 'Exemplo', 'valor' => 100.00]
        ]);
    }

    // Atualizar gasto inteiro
    public function update(Request $request, $id)
    {
        return response()->json([
            'message' => "Gasto {$id} atualizado",
            'data' => $request->all()
        ]);
    }

    // Atualizar parcialmente gasto
    public function updatePartial(Request $request, $id)
    {
        return response()->json([
            'message' => "Gasto {$id} atualizado parcialmente",
            'data' => $request->all()
        ]);
    }

    // Remover gasto
    public function destroy($id)
    {
        return response()->json([
            'message' => "Gasto {$id} removido"
        ], 204);
    }
}