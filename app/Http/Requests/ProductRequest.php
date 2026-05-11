<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajuste aqui se precisar de autorização específica
    }

    public function rules(): array
    {
        $product = $this->route('product');
    
        return [
            'title' => 'required|string|max:255',
    
            'link' => 'required|url|max:255|unique:products,link,' . ($product?->id ?? 'null'),
    
            'category_id' => 'required|exists:categories,id',
            'company_id'  => 'required|exists:companies,id',
    
            'coupon_id'   => 'nullable|exists:coupons,id',
    
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    
            'original_price' => 'nullable|numeric|min:0',
            'promo_price'    => 'required|numeric|min:0',
    
            'description' => 'nullable|string',
            'active'      => 'required|boolean',
            'inspired'    => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => "Campo título é obrigatório!",
            'link.required' => "Campo link é obrigatório!",
            'link.url' => "O link deve ser uma URL válida!",
            'link.unique' => "O link já está cadastrado!",
            'category_id.required' => "A categoria é obrigatória.",
            'category_id.exists' => "A categoria selecionada não existe.",
            'coupon_id.exists'     => "O cupom selecionado não existe.",
            'original_price.numeric' => "O preço original deve ser um número.",
            'promo_price.required' => "O preço promocional é obrigatório.",
            'promo_price.numeric' => "O preço promocional deve ser um número.",
            'active.boolean' => "O status deve ser verdadeiro ou falso.",
            'company_id.required' => "A empresa é obrigatória.",
            'company_id.exists' => "A empresa selecionada não existe.",
            'inspired.date'        => "A data de inspirado deve ser válida.",
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'categoria',
            'company_id'  => 'empresa',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'original_price' => $this->normalizeCurrency($this->original_price),
            'promo_price'    => $this->normalizeCurrency($this->promo_price),
        ]);
    }

    private function normalizeCurrency($value)
    {
        if (!$value) {
            return null;
        }
        // Remove R$, pontos de milhar e troca vírgula por ponto
        return floatval(str_replace(['R$', '.', ','], ['', '', '.'], $value));
    }
}