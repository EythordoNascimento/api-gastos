<?php

namespace App\Http\Controllers;

use App\Models\DespesaPE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DespesaPEController extends Controller
{
    // Importar dados do JSON para o banco
    public function importar()
    {
        try {
            $json = Storage::disk('local')->get('dados_pe.json');
            $dados = json_decode($json, true);

            // Detecta se existe o campo "campos" ou se é um array direto
            $registros = isset($dados['campos']) ? $dados['campos'] : $dados;

            $importados = 0;

            foreach ($registros as $item) {
                // Evita duplicação: checa se já existe pela ordem bancária
                $existe = DespesaPE::where('ordem_bancaria', $item['num_ordem_bancaria'])->exists();

                if (!$existe) {
                    DespesaPE::create([
                        'finalidade' => $item['finalidade_ordem_bancaria'] ?? '',
                        'credor' => $item['masc_nm_credor_ob'] ?? '',
                        'situacao' => $item['situacao'] ?? '',
                        'ordem_bancaria' => $item['num_ordem_bancaria'] ?? '',
                        'unidade_gestora' => $item['unidade_gestora'] ?? '',
                        'data' => isset($item['dt_lancamento']) ? substr($item['dt_lancamento'], 0, 10) : null,
                        'valor' => $item['vlr_ordem_bancaria'] ?? 0,
                        'numero_empenho' => $item['num_emp'] ?? ''
                    ]);
                    $importados++;
                }
            }

            return response()->json([
                'message' => 'Importação concluída com sucesso!',
                'total_importados' => $importados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao importar dados',
                'detalhes' => $e->getMessage()
            ], 500);
        }
    }

    // Listar todas as despesas com paginação
    public function index()
    {
        return response()->json([
            'message' => 'Lista de despesas reais de PE',
            'data' => DespesaPE::paginate(20) // paginação para não sobrecarregar
        ]);
    }

    // Ranking por unidade gestora
    public function ranking()
    {
        $ranking = DespesaPE::selectRaw('unidade_gestora, SUM(valor) as total')
            ->groupBy('unidade_gestora')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'message' => 'Ranking de despesas por unidade gestora em PE',
            'data' => $ranking
        ]);
    }

    // Filtro apenas por mês
public function filtro(Request $request)
{
    if (!$request->has('mes')) {
        return response()->json([
            'error' => 'Informe o mês (1 a 12) para filtrar'
        ], 400);
    }

    $mes = $request->mes;

    $query = DespesaPE::whereMonth('data', $mes)->get();

    return response()->json([
        'message' => "Despesas do mês $mes",
        'data' => $query
    ]);
}


    // Comparativo trimestral dentro de um ano
public function comparativo(Request $request)
{
    $ano = $request->ano ?? 2026; 

    $dados = DespesaPE::selectRaw('CEIL(MONTH(data)/3) as trimestre, SUM(valor) as total')
        ->whereYear('data', $ano)
        ->groupBy('trimestre')
        ->orderBy('trimestre')
        ->get();

    return response()->json([
        'message' => "Comparativo trimestral do ano $ano",
        'data' => $dados
    ]);
}


    // Indicadores automáticos
    public function indicadores()
    {
        return response()->json([
            'total' => DespesaPE::sum('valor'),
            'media' => DespesaPE::avg('valor'),
            'maior' => DespesaPE::max('valor'),
            'menor' => DespesaPE::min('valor')
        ]);
    }
}
