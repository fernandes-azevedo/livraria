<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssuntoRequest extends FormRequest
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
        // "Ignorando o 'codAs' do assunto atual na regra unique"
        $assuntoId = $this->assunto->codAs;

        return [
            'Descricao' => [
                'required',
                'string',
                'max:20',
                Rule::unique('Assunto', 'Descricao')->ignore($assuntoId, 'codAs'),
            ]
        ];
    }

    public function messages(): array // (Igual ao StoreAssuntoRequest)
    {
        return [
            'Descricao.required' => 'O campo Descrição é obrigatório.',
            'Descricao.max' => 'A descrição deve ter no máximo 20 caracteres.',
            'Descricao.unique' => 'Esta descrição de assunto já existe.',
        ];
    }
}
