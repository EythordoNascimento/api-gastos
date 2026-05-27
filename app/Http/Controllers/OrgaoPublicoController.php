<?php

namespace App\Http\Controllers;

use App\Models\OrgaoPublico;
use Illuminate\Http\Request;

class OrgaoPublicoController extends Controller
{
    public function index()
    {
        $orgaos = OrgaoPublico::all();

        return response()->json([
            'message' => 'Lista de órgãos públicos',
            'data' => $orgaos
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'   => 'required|string|max:255',
            'sigla'  => 'nullable|string|max:20',
            'codigo' => 'required|string|max:20|unique:orgaos_publicos,codigo',
            'esfera' => 'required|string|max:50'
        ]);

        $orgao = OrgaoPublico::create($validated);

        return response()->json([
            'message' => 'Órgão público criado com sucesso',
            'data'    => $orgao
        ], 201);
    }

    public function show(OrgaoPublico $orgao)
    {
        return response()->json([
            'message' => 'Detalhes do órgão',
            'data'    => $orgao
        ]);
    }

    public function update(Request $request, OrgaoPublico $orgao)
    {
        $validated = $request->validate([
            'nome'   => 'sometimes|string|max:255',
            'sigla'  => 'sometimes|string|max:20',
            'codigo' => 'sometimes|string|max:20|unique:orgaos_publicos,codigo,' . $orgao->id_orgao . ',id_orgao',
            'esfera' => 'sometimes|string|max:50'
        ]);

        $orgao->update($validated);

        return response()->json([
            'message' => 'Órgão público atualizado',
            'data'    => $orgao
        ]);
    }

    public function destroy(OrgaoPublico $orgao)
    {
        $orgao->delete();

        return response()->json([
            'message' => 'Órgão público removido com sucesso'
        ], 200);
    }

    //  Ranking
    public function ranking()
    {
        $ranking = OrgaoPublico::select(
            'orgaos_publicos.id_orgao',
            'orgaos_publicos.nome',
            'orgaos_publicos.sigla',
            'orgaos_publicos.esfera'
        )
        ->leftJoin('despesas', 'despesas.id_orgao', '=', 'orgaos_publicos.id_orgao')
        ->selectRaw('COALESCE(SUM(despesas.valor), 0) as total_gastos')
        ->groupBy('orgaos_publicos.id_orgao', 'orgaos_publicos.nome', 'orgaos_publicos.sigla', 'orgaos_publicos.esfera')
        ->orderByDesc('total_gastos')
        ->get();
    
        return response()->json([
            'message' => 'Ranking de órgãos por gastos',
            'data'    => $ranking
        ]);
    }
    

    }

