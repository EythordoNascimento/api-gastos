<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Gasto;
use App\Models\OrgaoPublico;

class GastoController extends Controller
{
    /**
     * Listar todos os gastos cadastrados manualmente
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
            'tipo' => 'required|string|max:255',   
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'fase' => 'nullable|string|max:50',
            'id_orgao' => 'nullable|exists:orgao_publico,id_orgao'
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
        return response()->json($gasto);
    }

    /**
     * Atualizar totalmente (PUT)
     */
    public function update(Request $request, Gasto $gasto)
    {
        $validated = $request->validate([
            'tipo' => 'sometimes|string|max:255',
            'valor' => 'sometimes|numeric|min:0.01',
            'data' => 'sometimes|date',
            'fase' => 'sometimes|string|max:50',
            'id_orgao' => 'sometimes|exists:orgao_publico,id_orgao'
        ]);

        $gasto->update($validated);

        return response()->json([
            'message' => 'Despesa atualizada',
            'data' => $gasto
        ]);
    }

    /**
     * Atualização parcial (PATCH)
     */
    public function updatePartial(Request $request, Gasto $gasto)
    {
        $gasto->fill($request->all())->save();

        return response()->json([
            'message' => 'Despesa atualizada parcialmente',
            'data' => $gasto
        ]);
    }

    /**
     * Deletar gasto
     */
    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return response()->json(null, 204);
    }

    /**
     * Ranking de gastos por órgão
     */
    public function ranking()
    {
        $ranking = Gasto::selectRaw('id_orgao, SUM(valor) as total')
            ->groupBy('id_orgao')
            ->orderByDesc('total')
            ->with('orgao')
            ->get();

        return response()->json([
            'message' => 'Ranking de despesas por órgão',
            'data' => $ranking
        ]);
    }

    /**
     * Gastos reais do Ministério da Educação (MEC)
     */
    public function educacao()
    {
        $token = env('PORTAL_TRANSPARENCIA_TOKEN', '3763500ce4c9581430d81c14bd9eefe5');

        $response = Http::withHeaders([
            'chave-api-dados' => $token
        ])->withoutVerifying()->get(
            'https://api.portaldatransparencia.gov.br/api-de-dados/despesas/por-orgao',
            [
                'codigoOrgao' => '26205', // MEC
                'ano' => 2024,
                'pagina' => 1,
                'codigoFuncao' => '12',        // Educação
                'codigoSubfuncao' => '365',    // Educação Básica
                'codigoNaturezaDespesa' => '339030' // Exemplo: Material de Consumo
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'Falha ao consultar o Portal da Transparência',
                'status' => $response->status(),
                'detalhes' => $response->body()
            ], 500);
        }

        return response()->json([
            'message' => 'Gastos reais do Ministério da Educação',
            'data' => $response->json()
        ]);
    }
}