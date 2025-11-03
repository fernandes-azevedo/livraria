<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivroRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // "Validação de todos os campos do Livro, incluindo o 'Valor'
        // e os arrays 'autores' e 'assuntos' que vêm dos selects."
        return [
            'Titulo' => 'required|string|max:40',
            'Editora' => 'required|string|max:40',
            'Edicao' => 'required|integer|min:1',
            'AnoPublicacao' => 'required|string|digits:4',
            'Valor' => 'required|numeric|min:0',

            // "Validação dos relacionamentos"
            'autores' => 'nullable|array',
            'autores.*' => 'exists:Autor,CodAu', // "Cada item deve existir na tabela 'Autor'"

            'assuntos' => 'nullable|array',
            'assuntos.*' => 'exists:Assunto,codAs', // "Cada item deve existir na tabela 'Assunto'"
        ];
    }


    public function messages(): array
    {
        return [
            // Regras do Livro
            'Titulo.required' => 'O campo Título é obrigatório.',
            'Titulo.max' => 'O Título deve ter no máximo 40 caracteres.',
            'Editora.required' => 'O campo Editora é obrigatório.',
            'Editora.max' => 'A Editora deve ter no máximo 40 caracteres.',
            'Edicao.required' => 'O campo Edição é obrigatório.',
            'Edicao.integer' => 'A Edição deve ser um número inteiro.',
            'Edicao.min' => 'A Edição deve ser pelo menos 1.',
            'AnoPublicacao.required' => 'O campo Ano de Publicação é obrigatório.',
            'AnoPublicacao.digits' => 'O Ano de Publicação deve ter exatamente 4 dígitos (ex: 2024).',
            'Valor.required' => 'O campo Valor é obrigatório.',
            'Valor.numeric' => 'O Valor deve ser um número.',
            'Valor.min' => 'O Valor não pode ser negativo.',

            // Regras dos Relacionamentos
            'autores.array' => 'O campo Autores deve ser uma seleção válida.',
            'autores.*.exists' => 'O autor selecionado é inválido.',

            'assuntos.array' => 'O campo Assuntos deve ser uma seleção válida.',
            'assuntos.*.exists' => 'O assunto selecionado é inválido.',
        ];
    }
}
