<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KeywordProductRequest extends FormRequest
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
                // Verifica duplicidade na tabela keyword_products
                Rule::unique('keyword_products')
                    ->where('category_id', $this->category_id) // Permite mesmo nome se for categorias diferentes
                    ->ignore($this->keywordProduct), // Ignora o ID atual na edição
            ],
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome da palavra-chave é obrigatório.',
            'name.unique' => 'Essa palavra-chave já existe nesta categoria.',
            'category_id.required' => 'Selecione uma categoria.',
            'category_id.exists' => 'A categoria selecionada é inválida.',
        ];
    }
}