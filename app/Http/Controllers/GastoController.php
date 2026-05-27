<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gasto;

class GastoController extends Controller
{
    /**
     * Listar todos os gastos
     */
    public function index()
    {
        $gastos = Gasto::with('orgao')->get();

        return response()->json([
            'message' => 'Lista de despesas cadastradas',
            'data' => $gastos
        ]);
    }

    /**
     * Criar gasto manual
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'fase' => 'nullable|string|max:50',
            'id_orgao' => 'nullable|exists:orgaos_publicos,id_orgao'
        ]);

        $gasto = Gasto::create($validated);

        return response()->json([
            'message' => 'Despesa registrada com sucesso',
            'data' => $gasto
        ], 201);
    }

    /**
     * Mostrar gasto específico
     */
    public function show(Gasto $gasto)
    {
        return response()->json([
            'message' => 'Despesa encontrada',
            'data' => $gasto->load('orgao')
        ]);
    }

    /**
     * Atualizar totalmente (PUT)
     */
    public function update(Request $request, Gasto $gasto)
    {
        $validated = $request->validate([
            'valor' => 'sometimes|numeric|min:0.01',
            'data' => 'sometimes|date',
            'fase' => 'sometimes|nullable|string|max:50',
            'id_orgao' => 'sometimes|nullable|exists:orgaos_publicos,id_orgao'
        ]);

        $gasto->update($validated);

        return response()->json([
            'message' => 'Despesa atualizada com sucesso',
            'data' => $gasto
        ]);
    }

    /**
     * Atualização parcial (PATCH)
     */
    public function updatePartial(Request $request, Gasto $gasto)
    {
        $validated = $request->validate([
            'valor' => 'sometimes|numeric|min:0.01',
            'data' => 'sometimes|date',
            'fase' => 'sometimes|nullable|string|max:50',
            'id_orgao' => 'sometimes|nullable|exists:orgaos_publicos,id_orgao'
        ]);

        $gasto->update($validated);

        return response()->json([
            'message' => 'Despesa atualizada parcialmente',
            'data' => $gasto
        ]);
    }

    /**
     * Excluir gasto
     */
    public function destroy(Gasto $gasto)
    {
        $gasto->delete();

        return response()->json([
            'message' => 'Despesa removida com sucesso'
        ], 200);
    }

    /**
     * Ranking de gastos por órgão
     */
    public function ranking()
    {
        $ranking = Gasto::selectRaw('id_orgao, SUM(valor) as total')
            ->with('orgao')
            ->groupBy('id_orgao')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'message' => 'Ranking de despesas por órgão',
            'data' => $ranking
        ]);
    }
}
