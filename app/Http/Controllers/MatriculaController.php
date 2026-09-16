<?php

namespace App\Http\Controllers;

use App\Actions\ProcessarMatriculaAction;
use App\Http\Requests\MatriculaRequest;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index()
    {
        return response()->json(
            Matricula::with(['aluno', 'curso'])->get()
        );
    }

    public function store(MatriculaRequest $request, ProcessarMatriculaAction $action)
    {
        $aluno = Aluno::findOrFail($request->aluno_id);
        $curso = Curso::findOrFail($request->curso_id);

        $matricula = $action->execute($aluno, $curso);

        return response()->json($matricula, 201);
    }

    public function show(Matricula $matricula)
    {
        return response()->json(
            $matricula->load(['aluno', 'curso'])
        );
    }

    public function cancelar(Matricula $matricula)
    {
        $matricula->update([
            'status' => 'cancelada'
        ]);

        return response()->json([
            'message' => 'Matrícula cancelada com sucesso!'
        ]);
    }
}
