<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutorRequest extends FormRequest
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
        // "Para a atualização (UPDATE), a regra 'unique' é especial.
        // Ela precisa verificar se o 'Nome' é único, IGNORANDO
        // o 'CodAu' do autor que esta sendo editado. Usei o 'Rule::unique'
        // para construir essa lógica de forma limpa."

        // $this->autor->CodAu pega o 'CodAu' do autor vindo da rota.
        $autorId = $this->autor->CodAu;

        return [
            'Nome' => [
                'required',
                'string',
                'max:40',
                // "unique:tabela,coluna->ignore(ID_a_ignorar, coluna_do_id)"
                Rule::unique('Autor', 'Nome')->ignore($autorId, 'CodAu'),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'Nome.required' => 'O campo Nome é obrigatório.',
            'Nome.unique' => 'Este nome de autor já está em uso.',
        ];
    }
}
