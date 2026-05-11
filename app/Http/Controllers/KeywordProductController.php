<?php

namespace App\Http\Controllers;

use App\Models\KeywordProduct;
use App\Models\Category;
use App\Http\Requests\KeywordProductRequest; // 1. Importação da Request
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class KeywordProductController extends Controller
{
    /**
     * Lista todas as palavras-chave de produtos/categorias.
     */
    public function index(Request $request)
    {
        $query = KeywordProduct::with('category');
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        $keywords = $query
            ->orderBy('updated_at', 'desc')
            ->paginate(12)
            ->appends(['name' => $request->name]);
    
        Log::info('Listagem de keywords de produtos.', [
            'search' => $request->name,
            'action_user_id' => Auth::id(),
        ]);
    
        return view('keyword_products.index', [
            'keywords' => $keywords,
            'name' => $request->name,
        ]);
    }

    /**
     * Mostra uma palavra-chave específica.
     */
    public function show($slug)
    {
        // Busca onde a coluna 'slug' é igual ao $slug recebido
        $keyword = KeywordProduct::with('category')
                    ->where('slug', $slug)
                    ->firstOrFail(); // Retorna erro 404 se não achar
    
        return view('keyword_products.show', compact('keyword'));
    }

    /**
     * Carrega o formulário para criar nova palavra-chave.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        Log::info('Acessar formulário de criação de keyword produto.', [
            'action_user_id' => Auth::id()
        ]);

        return view('keyword_products.create', [
            'menu' => 'keyword_products',
            'categories' => $categories
        ]);
    }

    /**
     * Armazena uma nova palavra-chave.
     */
    // 2. Injeção da KeywordProductRequest
    public function store(KeywordProductRequest $request) 
    {
        // Nota: A validação ocorre automaticamente aqui. 
        // Se falhar, o Laravel redireciona de volta com os erros.

        try {
            // Pega apenas os dados validados (name, category_id)
            $data = $request->validated(); 
            
            // Geramos o slug manualmente para garantir, ou deixamos o Model fazer no booted()
            // Como 'slug' não está no validated(), adicionamos ao array:
            $data['slug'] = Str::slug($data['name']);

            $keywordProduct = KeywordProduct::create($data);

            Log::info('Keyword de produto criada.', [
                'keyword_id' => $keywordProduct->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('keyword_products.show', ['keywordProduct' => $keywordProduct])
                ->with('success', 'Palavra-chave vinculada à categoria com sucesso!');
                
        } catch (Exception $e) {
            Log::notice('Falha ao criar keyword de produto.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()->withInput()->with('error', 'Erro ao cadastrar palavra-chave!');
        }
    }

    /**
     * Carrega o formulário de edição.
     */
    public function edit($slug)
    {
        // 1. Busca a palavra-chave (Produto) usando o SLUG
        $keyword = \App\Models\KeywordProduct::where('slug', $slug)->firstOrFail();

        // 2. Busca as categorias para preencher o <select> no formulário
        $categories = \App\Models\Category::orderBy('name')->get();

        // 3. Envia 'keyword' e 'categories' para a view
        // Isso resolve o erro "Undefined variable $keyword"
        return view('keyword_products.edit', compact('keyword', 'categories'));
    }

    /**
     * Atualiza uma palavra-chave existente.
     */
    // 3. Injeção da KeywordProductRequest
    public function update(KeywordProductRequest $request, KeywordProduct $keywordProduct)
    {
        // A validação, inclusive a verificação de unique ignorando o ID atual, 
        // já foi feita dentro do Request.

        try {
            $data = $request->validated();
            
            // Atualiza o slug caso o nome tenha mudado
            $data['slug'] = Str::slug($data['name']);

            $keywordProduct->update($data);

            Log::info('Keyword de produto atualizada.', [
                'keyword_id' => $keywordProduct->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('keyword_products.show', ['keywordProduct' => $keywordProduct])
                ->with('success', 'Atualizado com sucesso!');
                
        } catch (Exception $e) {
            Log::notice('Falha ao atualizar keyword de produto.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()->withInput()->with('error', 'Erro ao atualizar!');
        }
    }

    /**
     * Remove uma palavra-chave.
     */
    public function destroy(KeywordProduct $keywordProduct)
    {
        try {
            $keywordProduct->delete();

            Log::info('Keyword de produto excluída.', [
                'keyword_id' => $keywordProduct->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('keyword_products.index')
                ->with('success', 'Excluído com sucesso!');
        } catch (Exception $e) {
            Log::notice('Falha ao excluir keyword de produto.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()->with('error', 'Erro ao excluir!');
        }
    }
}