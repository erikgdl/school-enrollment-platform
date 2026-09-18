<?php

namespace App\Repository;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;

class MatriculaRepository
{
    public function JaMatricula(Aluno $aluno, Curso $curso) {

        $jaMatriculado = Matricula::where('aluno_id', $aluno->id)
            ->where('curso_id', $curso->id)
            ->where('status', 'aprovada')
            ->exists();

        return $jaMatriculado;
    }


}
