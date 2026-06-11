<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gasto;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::with('orgao')->get();

        return response()->json([
            'success' => true,
            'message' => 'Lista de despesas cadastradas',
            'data'    => $gastos
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'valor'    => 'required|numeric|min:0.01',
            'data'     => 'required|date',
            'fase'     => 'nullable|string|max:50|min:1',
            'id_orgao' => 'nullable|exists:orgaos_publicos,id_orgao'
        ]);

        $gasto = Gasto::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Despesa registrada com sucesso',
            'data'    => $gasto
        ], 201);
    }

    public function show($id)
    {
        $gasto = Gasto::with('orgao')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detalhes da despesa',
            'data'    => $gasto
        ]);
    }

    public function update(Request $request, $id)
    {
        $gasto = Gasto::findOrFail($id);

        $validated = $request->validate([
            'valor'    => 'sometimes|required|numeric|min:0.01',
            'data'     => 'sometimes|required|date',
            'fase'     => 'sometimes|nullable|string|max:50|min:1',
            'id_orgao' => 'sometimes|nullable|exists:orgaos_publicos,id_orgao'
        ]);

        $gasto->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Despesa atualizada com sucesso',
            'data'    => $gasto
        ]);
    }

    public function destroy($id)
    {
        $gasto = Gasto::findOrFail($id);
        $gasto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Despesa removida com sucesso'
        ], 200);
    }

    public function ranking()
    {
        $ranking = Gasto::selectRaw('id_orgao, COALESCE(SUM(valor), 0) as total_gastos')
            ->with('orgao')
            ->groupBy('id_orgao')
            ->orderByDesc('total_gastos')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Ranking de despesas por órgão',
            'data'    => $ranking
        ]);
    }
}
