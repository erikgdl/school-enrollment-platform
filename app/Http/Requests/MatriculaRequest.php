<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MatriculaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'aluno_id' => ['required', 'exists:alunos,id'],
            'curso_id' => ['required', 'exists:cursos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'aluno_id.required' => 'O aluno é obrigatório.',
            'aluno_id.exists' => 'O aluno informado não existe.',

            'curso_id.required' => 'O curso é obrigatório.',
            'curso_id.exists' => 'O curso informado não existe.',
        ];
    }
}
