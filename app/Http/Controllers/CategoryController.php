<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * 📋 Lista todas as categorias
     */
    public function index(Request $request)
    {
        // 1. Inicia a Query
        $query = Category::query();

        // 2. Verifica se o campo 'name' foi preenchido na busca
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // 3. Executa a query com paginação e ordenação
        // o withQueryString() serve para não perder a busca ao mudar de página
        $categories = $query->orderBy('name', 'ASC')
                            ->paginate(10)
                            ->withQueryString();

        // 4. Retorna para a View
        return view('categories.index', compact('categories'));
    }

    /**
     * Mostra uma categoria específica.
     */
    public function show(Category $category)
    {
        Log::info('Visualizar categoria.', ['action_user_id' => Auth::id()]);
        
        // Alterado para a pasta 'categories'
        return view('categories.show', [
            'menu' => 'categories', 
            'category' => $category
        ]);
    }

    /**
     * ➕ Formulário de criação
     */
    public function create()
    {
        return view('categories.create', [
            'menu' => 'categories'
        ]);
    }

    /**
     * 💾 Salva nova categoria
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create([
                'name'   => $request->name,
                'active' => $request->active
                // O slug é gerado no Model automaticamente (Ótimo para SEO!)
            ]);

            Log::info('Categoria cadastrada.', [
                'category_id' => $category->id,
                'action_user_id' => Auth::id()
            ]);

            // Atualizado o nome da rota para o padrão plural correto
            return redirect()
                ->route('categories.show', $category)
                ->with('success', 'Categoria cadastrada com sucesso!');

        } catch (Exception $e) {
            Log::notice('Erro ao cadastrar categoria.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Categoria não cadastrada!');
        }
    }

    /**
     * ✏️ Formulário de edição
     */
    public function edit(Category $category)
    {
        Log::info('Editar categoria.', [
            'category_id' => $category->id,
            'action_user_id' => Auth::id()
        ]);

        return view('categories.edit', [
            'menu' => 'categories',
            'category' => $category
        ]);
    }

    /**
     * 🔄 Atualiza categoria
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category->update([
                'name'   => $request->name,
                'active' => $request->active
            ]);

            Log::info('Categoria atualizada.', [
                'category_id' => $category->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('categories.show', $category)
                ->with('success', 'Categoria editada com sucesso!');

        } catch (Exception $e) {
            Log::notice('Erro ao editar categoria.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Categoria não editada!');
        }
    }

    /**
     * Remove uma categoria.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            Log::info('Categoria apagada.', ['category_id' => $category->id, 'action_user_id' => Auth::id()]);
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoria apagada com sucesso!');

        } catch (Exception $e) {
            Log::notice('Categoria não apagada.', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Categoria não apagada!');
        }
    }
}