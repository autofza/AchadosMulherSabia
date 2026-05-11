<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    /**
     * Lista todos os blogs.
     */
    public function index()
    {
        // Recuperar os registros do banco dados
        $blogs = Blog::orderBy('id', 'DESC')->paginate(10);

        // Carregar a view 
        return view('blogs.index', ['menu' => 'blogs', 'blogs' => $blogs]);
    }

    /**
     * Mostra um blog específico.
     */
    public function show(Blog $blog)
    {
        // Salvar log
        Log::info('Visualizar as blogs.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('blogs.show', ['menu' => 'blogs', 'blog' => $blog]);
    }

    // Carregar o formulário cadastrar novo blog
    public function create()
    {

        // Enviar para a view
        return view('blogs.create', ['menu' => 'blogs']);
    }

    /** 
     * Cria um novo blog.
     */
    public function store(BlogRequest $request)
    {
        try {
            $data = $request->validated();
    
            // Upload da imagem
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                File::ensureDirectoryExists(public_path('uploads/imgBlogs'));
                $imageFile->move(public_path('uploads/imgBlogs'), $imageName);
                $data['image'] = 'uploads/imgBlogs/' . $imageName;
            } else {
                $data['image'] = 'uploads/imgSem.jpg';
            }
    
            $blog = Blog::create($data);
    
            Log::info('Blog cadastrado.', [
                'blog_id' => $blog->id,
                'slug' => $blog->slug,
            ]);
    
            return redirect()
                ->route('blogs.show', $blog) // ✅ SEO PERFEITO
                ->with('success', 'Blog cadastrado com sucesso!');
    
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar blog.', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Blog não cadastrado!');
        }
    }
    
    /**
     * Carregar o formulário editar blog.
     */
    public function edit(Blog $blog)
    {
        // Carregar a view 
        return view('blogs.edit', ['menu' => 'blogs', 'blog' => $blog]);
    }

    /**
     * Atualiza um blog existente.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        try {
            $data = $request->validated();
    
            // Upload de nova imagem
            if ($request->hasFile('image')) {
    
                // Remove imagem antiga (se não for padrão)
                if (
                    $blog->image &&
                    $blog->image !== 'uploads/imgSem.jpg' &&
                    file_exists(public_path($blog->image))
                ) {
                    @unlink(public_path($blog->image));
                }
    
                $image = $request->file('image');
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                File::ensureDirectoryExists(public_path('uploads/imgBlogs'));
                $image->move(public_path('uploads/imgBlogs'), $imageName);
    
                $data['image'] = 'uploads/imgBlogs/' . $imageName;
    
            } else {
                // Mantém imagem antiga
                $data['image'] = $blog->image ?? 'uploads/imgSem.jpg';
            }
    
            // Atualiza o blog
            $blog->update($data);
    
            Log::info('Blog editado.', [
                'blog_id' => $blog->id,
                'slug'    => $blog->slug,
            ]);
    
            return redirect()
                ->route('blogs.show', $blog) // ✅ URL COM SLUG
                ->with('success', 'Blog atualizado com sucesso!');
    
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar Blog: ' . $e->getMessage());
        }
    }

    /**
     * Remove um blog.
     */
    public function destroy(Blog $blog)
    {
        try {
            if (!empty($blog->image)) {
                $filePath = public_path($blog->image);

                /*
                dd([
                    'Caminho que vai tentar apagar' => $filePath,
                    'Existe arquivo?' => File::exists($filePath),
                ]); 
                */

                if (File::exists($filePath)) {
                    File::delete($filePath);
                    Log::info('Imagem apagada: ' . $filePath);
                } else {
                    Log::warning('Imagem não encontrada: ' . $filePath);
                }
            }

            $blog->delete();

            // Salvar log
            Log::info('Blog apagado.', ['blog_id' => $blog->id, 'action_user_id' => Auth::id()]);

            return redirect()->route('blogs.index')->with('success', 'Blog apagada com sucesso!');
            
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Blog não apagado.', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Erro ao apagar o Blog: ' . $e->getMessage());
        }
    }
}
