<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutorRequest extends FormRequest
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
        // "A regra de validação agora usa o campo 'Nome' e verifica
        // a unicidade na tabela 'Autor', coluna 'Nome'."
        return [
            'Nome' => 'required|string|max:40|unique:Autor,Nome'
        ];
    }

    public function messages(): array
    {
        return [
            'Nome.required' => 'O campo Nome é obrigatório.',
            'Nome.unique' => 'Este autor já está cadastrado.',
        ];
    }
}
