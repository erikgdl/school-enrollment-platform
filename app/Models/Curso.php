<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model {

    protected $fillable = [
        'nome',
        'descricao',
        'vagas',
        'vagas_disponiveis',
        'valor',
    ];

    public function matriculas(): HasMany {
        return $this->hasMany(Matricula::class);
    }

}

