<?php

namespace App\Http\Controllers;

use App\Models\KeywordCompany;
use App\Models\Company;
use App\Http\Requests\KeywordCompanyRequest; // 1. Importação da Request
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class KeywordCompanyController extends Controller
{
    /**
     * Lista todas as palavras-chave de empresas.
     */
    public function index(Request $request)
    {
        $query = KeywordCompany::with('company');
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        $keywords = $query
            ->orderBy('updated_at', 'desc')
            ->paginate(12)
            ->appends(['name' => $request->name]);
    
        Log::info('Listagem de keywords de empresas.', [
            'search' => $request->name,
            'action_user_id' => Auth::id(),
        ]);
    
        return view('keyword_companies.index', [
            'keywords' => $keywords,
            'name' => $request->name,
        ]);
    }

    /**
     * Mostra uma palavra-chave específica.
     */
    public function show($slug)
    {
        // Busca a empresa onde a coluna 'slug' é igual ao valor recebido na URL
        $keyword = \App\Models\KeywordCompany::with('company')
                    ->where('slug', $slug)
                    ->firstOrFail(); // Se não achar o slug, dá erro 404

        // Envia a variável $keyword para a view (Isso resolve o erro "Undefined variable")
        return view('keyword_companies.show', compact('keyword'));
    }

    /**
     * Carrega o formulário para criar nova palavra-chave.
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();

        Log::info('Acessar formulário de criação de keyword empresa.', [
            'action_user_id' => Auth::id()
        ]);

        return view('keyword_companies.create', [
            'menu' => 'keyword_companies',
            'companies' => $companies
        ]);
    }

    /**
     * Armazena uma nova palavra-chave.
     */
    // 2. Injeção da KeywordCompanyRequest
    public function store(KeywordCompanyRequest $request)
    {
        // A validação ocorre automaticamente aqui.

        try {
            // Pega apenas os dados validados (name, company_id)
            $data = $request->validated();
            
            // Adiciona o slug manualmente (pois não vem do form validado)
            $data['slug'] = Str::slug($data['name']);

            $keywordCompany = KeywordCompany::create($data);

            Log::info('Keyword de empresa criada.', [
                'keyword_id' => $keywordCompany->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('keyword_companies.show', ['keywordCompany' => $keywordCompany])
                ->with('success', 'Palavra-chave vinculada à empresa com sucesso!');
                
        } catch (Exception $e) {
            Log::notice('Falha ao criar keyword de empresa.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()->withInput()->with('error', 'Erro ao cadastrar palavra-chave!');
        }
    }

    /**
     * Carrega o formulário de edição.
     */
    public function edit($slug) // Ou ($id) se sua rota ainda usar ID
    {
        // 1. Busca o registro da Palavra-chave pelo Slug
        $keyword = \App\Models\KeywordCompany::where('slug', $slug)->firstOrFail();

        // 2. Busca a lista de Empresas para preencher o <select> no formulário
        $companies = \App\Models\Company::orderBy('name')->get();

        // 3. Envia AMBAS as variáveis para a view: 
        // 'keyword' (para os dados do form) e 'companies' (para o dropdown)
        return view('keyword_companies.edit', compact('keyword', 'companies'));
    }

    /**
     * Atualiza uma palavra-chave existente.
     */
    // 3. Injeção da KeywordCompanyRequest
    public function update(KeywordCompanyRequest $request, KeywordCompany $keywordCompany)
    {
        // Validação automática feita pela Request

        try {
            $data = $request->validated();
            
            // Atualiza o slug baseada no novo nome
            $data['slug'] = Str::slug($data['name']);

            $keywordCompany->update($data);

            Log::info('Keyword de empresa atualizada.', [
                'keyword_id' => $keywordCompany->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('keyword_companies.show', ['keywordCompany' => $keywordCompany])
                ->with('success', 'Atualizado com sucesso!');
                
        } catch (Exception $e) {
            Log::notice('Falha ao atualizar keyword de empresa.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()->withInput()->with('error', 'Erro ao atualizar!');
        }
    }

    /**
     * Remove uma palavra-chave.
     */
    public function destroy(KeywordCompany $keywordCompany)
    {
        try {
            $keywordCompany->delete();

            Log::info('Keyword de empresa excluída.', [
                'keyword_id' => $keywordCompany->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('keyword_companies.index')
                ->with('success', 'Excluído com sucesso!');
        } catch (Exception $e) {
            Log::notice('Falha ao excluir keyword de empresa.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()->with('error', 'Erro ao excluir!');
        }
    }
}