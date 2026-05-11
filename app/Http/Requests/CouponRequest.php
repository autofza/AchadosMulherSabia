<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'code' => 'required|string|unique:coupons,code,' . $this->coupon?->id,
            'value' => 'required|string|max:255',
            'company_id' => 'required|exists:companys,id',
            'active' => 'sometimes|boolean',
            'link' => 'nullable|url|max:255', // adicionado
        ];
    }

    /**
     * Define mensagens personalizadas para as regras de validação.
     *
     * @return array<string, string> Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'O Código do cupom é obrigatório!',
            'value.required' => 'O valor do cupom é obrigatório!',
            'company_id.required' => 'A empresa é obrigatória.',
            'company_id.exists' => 'A empresa selecionada não foi encontrada.',
            'active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
            'link.url' => 'O link informado não é válido.',
            'link.max' => 'O link não pode ter mais de 255 caracteres.',
        ];
    }
}
