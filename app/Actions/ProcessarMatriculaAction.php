<?php

namespace App\Actions;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;
use App\Repository\MatriculaRepository;
use App\Events\MatriculaAprovada;
use Illuminate\Support\Facades\DB;

class ProcessarMatriculaAction
{

    public MatriculaRepository $matriculaRepository;
    public function execute(Aluno $aluno, Curso $curso): Matricula
    {

        // @transactional
        return DB::transaction(function () use ($aluno, $curso) {

            //se tive dar exception
            if ($aluno->possui_pendencia) {
                throw new \Exception('Aluno possui pendência financeira');
            }

            //se tive dar exception
            if ($curso->vagas_disponiveis <= 0) {
                throw new \Exception('Curso não possui vagas disponíveis.');
            }

            $jaMatriculado = $this->matriculaRepository->JaMatricula($aluno, $curso);


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
