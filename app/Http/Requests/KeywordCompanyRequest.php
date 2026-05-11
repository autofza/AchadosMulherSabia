<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KeywordCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Verifica duplicidade na tabela keyword_companies
                Rule::unique('keyword_companies')
                    ->where('company_id', $this->company_id) // Permite mesmo nome se for empresas diferentes
                    ->ignore($this->keywordCompany), // Ignora o ID atual na edição
            ],
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome da palavra-chave é obrigatório.',
            'name.unique' => 'Essa palavra-chave já existe para esta empresa.',
            'company_id.required' => 'Selecione uma empresa.',
            'company_id.exists' => 'A empresa selecionada é inválida.',
        ];
    }
}