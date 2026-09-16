<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlunoRequest;
use App\Models\Aluno;

class AlunoController extends Controller
{
    public function index()
    {
        return response()->json(Aluno::all());
    }

    public function store(AlunoRequest $request)
    {
        $aluno = Aluno::create($request->validated());

        return response()->json($aluno, 201);
    }

    public function show(Aluno $aluno)
    {
        return response()->json($aluno);
    }

    public function update(AlunoRequest $request, Aluno $aluno)
    {
        $aluno->update($request->validated());

        return response()->json($aluno);
    }

    public function destroy(Aluno $aluno)
    {
        $aluno->delete();

        return response()->json([
            'message' => 'Aluno excluído com sucesso!'
        ]);
    }
}
