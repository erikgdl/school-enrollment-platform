<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'possui_pendencia',
    ];

    public function matricula(): HasMany {
        return $this->hasMany(Matricula::class);
    }
}
