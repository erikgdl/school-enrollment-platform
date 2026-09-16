<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CursoRequest extends FormRequest
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
            'nome' => ['required', 'string'],
            'descricao' => ['nullable', 'string'],
            'vagas' => ['required', 'integer', 'min:1'],
            'valor' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do curso é obrigatório.',
            'nome.string' => 'O nome do curso deve ser um texto.',

            'descricao.string' => 'A descrição deve ser um texto.',

            'vagas.required' => 'A quantidade de vagas é obrigatória.',
            'vagas.integer' => 'A quantidade de vagas deve ser um número inteiro.',
            'vagas.min' => 'O curso precisa ter pelo menos 1 vaga.',

            'valor.required' => 'O valor do curso é obrigatório.',
            'valor.numeric' => 'O valor do curso deve ser um número.',
            'valor.min' => 'O valor do curso não pode ser negativo.',
        ];
    }

}
