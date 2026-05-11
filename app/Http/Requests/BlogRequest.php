<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pode ajustar para usar policies se necessário
    }

    public function rules(): array
    {

        $blogId = $this->route('blog') ? $this->route('blog')->id : null;

        return [
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'image'        => $blogId
                ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|required_without:existing_image' // update
                : 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // create
            'published'    => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max'      => 'O título não pode ter mais de 255 caracteres.',

            'content.required' => 'O conteúdo é obrigatório.',

            'image.image'   => "O arquivo deve ser uma imagem!",
            'image.mimes'   => "Tipos permitidos: jpeg, png, jpg, gif.",
            'image.max'     => "A imagem deve ter no máximo 2MB.",

            'published.boolean'   => 'O campo publicado deve ser verdadeiro ou falso.',
            'published_at.date'   => 'A data de publicação deve ser válida.',
        ];
    }
}
