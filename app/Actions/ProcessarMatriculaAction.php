<?php

namespace App\Actions;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;
use App\Events\MatriculaAprovada;
use Illuminate\Support\Facades\DB;

class ProcessarMatriculaAction
{
    public function execute(Aluno $aluno, Curso $curso): Matricula
    {
        return DB::transaction(function () use ($aluno, $curso) {
            if ($aluno->possui_pendencia) {
                throw new \Exception('Aluno possui pendência financeira');
            }

            if ($curso->vagas_disponiveis <= 0) {
                throw new \Exception('Curso não possui vagas disponíveis.');
            }

            $jaMatriculado = Matricula::where('aluno_id', $aluno->id)
                ->where('curso_id', $curso->id)
                ->where('status', 'aprovada')
                ->exists();

            if ($jaMatriculado) {
                throw new \Exception('Aluno já está matriculado nesse curso.');
            }

            $matricula = Matricula::create([
                'aluno_id' => $aluno->id,
                'curso_id' => $curso->id,
                'status' => 'aprovada',
                'data_matricula' => now(),
            ]);


            $curso->decrement('vagas_disponiveis');

            event(new MatriculaAprovada($matricula));

            return $matricula;
        });
    }
}
