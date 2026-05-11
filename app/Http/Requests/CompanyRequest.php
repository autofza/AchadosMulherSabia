<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe de requisição para validação de Empresas.
 *
 * Responsável por definir as regras de validação e mensagens de erro 
 * para operações relacionadas a empresas.
 *
 * @package App\Http\Requests
 */
class CompanyRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     *
     * @return bool Retorna true para permitir a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retorna as regras de validação aplicáveis à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> 
     * Regras de validação.
     */
    public function rules(): array
    {
        // Pega o parâmetro da rota (pode ser o Objeto Company ou apenas o ID)
        $company = $this->route('company');
        
        // Garante que temos o ID (inteiro) para ignorar na validação 'unique'
        $id = is_object($company) ? $company->id : $company;

        return [
            'name' => 'required',
            'soon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // CORREÇÃO AQUI: 
            // 1. Mudado de 'companys' para 'companies'
            // 2. Usando a variável $id tratada acima
            'link' => 'required|unique:companies,link,' . $id,
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
            'name.required' => "Campo nome é obrigatório!",
            'soon.required' => "O logo da loja é obrigatório!",
            'soon.image' => "O arquivo deve ser uma imagem!",
            'soon.mimes' => "Tipos permitidos: jpeg, png, jpg, gif.",
            'soon.max' => "A imagem deve ter no máximo 2MB.",
            'link.required' => "Campo link é obrigatório!",
            'link.unique' => "O link já está cadastrado!",
        ];
    }
}