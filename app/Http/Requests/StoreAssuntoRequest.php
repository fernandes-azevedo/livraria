<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssuntoRequest extends FormRequest
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
        return [
            // "Validação para Descricao, max:20 (do ERD) e unique na tabela Assunto"
            'Descricao' => 'required|string|max:20|unique:Assunto,Descricao'
        ];
    }

    public function messages(): array
    {
        return [
            'Descricao.required' => 'O campo Descrição é obrigatório.',
            'Descricao.max' => 'A descrição deve ter no máximo 20 caracteres.',
            'Descricao.unique' => 'Esta descrição de assunto já existe.',
        ];
    }
}
