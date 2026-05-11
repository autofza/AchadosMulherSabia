<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Se quiser permitir só usuários logados, troque para true e faça controle por policies
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name' => 'nullable|string',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',
            'active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
        ];
    }
}
