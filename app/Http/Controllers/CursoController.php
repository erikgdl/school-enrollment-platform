<?php

namespace App\Http\Controllers;

use App\Http\Requests\CursoRequest;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        return response()->json(Curso::all());
    }

    public function store(CursoRequest $request)
    {
        $dados = $request->validated();

        $curso = Curso::create([
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'vagas' => $dados['vagas'],
            'vagas_disponiveis' => $dados['vagas'],
            'valor' => $dados['valor'],
        ]);

        return response()->json($curso, 201);
    }

    public function show(Curso $curso)
    {
        return response()->json($curso);
    }

    public function update(Request $request, Curso $curso)
    {
        $curso->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'vagas' => $request->vagas,
            'vagas_disponiveis' => $request->vagas_disponiveis,
            'valor' => $request->valor,
        ]);

        return response()->json($curso);
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();

        return response()->json([
            'message' => 'Curso excluído com sucesso!'
        ]);
    }
}
