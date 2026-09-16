<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\MatriculaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AlunoController::class)->group(function () {
    Route::get('/alunos', 'index')->name('alunos.index');
    Route::post('/alunos', 'store')->name('alunos.store');
    Route::get('/alunos/{aluno}', 'show')->name('alunos.show');
    Route::put('/alunos/{aluno}', 'update')->name('alunos.update');
    Route::delete('/alunos/{aluno}', 'destroy')->name('alunos.destroy');
});

Route::controller(CursoController::class)->group(function () {
    Route::get('/cursos', 'index')->name('cursos.index');
    Route::post('/cursos', 'store')->name('cursos.store');
    Route::get('/cursos/{curso}', 'show')->name('cursos.show');
    Route::put('/cursos/{curso}', 'update')->name('cursos.update');
    Route::delete('/cursos/{curso}', 'destroy')->name('cursos.destroy');
});

Route::controller(MatriculaController::class)->group(function () {
    Route::get('/matriculas', 'index')->name('matriculas.index');
    Route::post('/matriculas', 'store')->name('matriculas.store');
    Route::get('/matriculas/{matricula}', 'show')->name('matriculas.show');
    Route::post('/matriculas/{matricula}/cancelar', 'cancelar')->name('matriculas.cancelar');
});
