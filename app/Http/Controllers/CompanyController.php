<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    // Listar as Empresas
    public function index()
    {
        // Recuperar os registros do banco dados
        // Ajuste: Variável renomeada para o plural correto ($companies)
        $companies = Company::orderBy('id', 'DESC')->paginate(10);

        // Salvar log
        Log::info('Listar as Empresas.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        // OBS: Mantive 'companies.index' assumindo que a pasta da view ainda tem o nome antigo.
        return view('companies.index', ['menu' => 'companies', 'companies' => $companies]);
    }

    // Visualizar os detalhes da empresa
    public function show(Company $company)
    {
        // Salvar log
        Log::info('Visualizar a Empresa.', ['company_id' => $company->id]);

        return view('companies.show', ['menu' => 'companies', 'company' => $company]);
    }

    // Carregar o formulário cadastrar nova empresa
    public function create()
    {
        Log::info('Acesso a página de cadastrar a Empresa!');
        return view('companies.create', ['menu' => 'companies']);
    }

    // No método STORE (Cadastrar)
    public function store(CompanyRequest $request)
    {
        try {
            $data = $request->validated();
            
            // 1. Define o caminho relativo e físico
            $folderName = 'uploads/imgCompanies';
            $destinationPath = public_path($folderName);

            // 2. Upload da Imagem
            if ($request->hasFile('soon')) {
                // Cria a pasta se não existir
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true, true);
                }

                $imageFile = $request->file('soon');
                $nameArquivo = uniqid() . '-soon.' . $imageFile->getClientOriginalExtension();
                
                $imageFile->move($destinationPath, $nameArquivo);
                
                // Salva no array de dados
                $data['soon'] = $folderName . '/' . $nameArquivo;
            }

            // 3. Gera o Slug
            $data['slug'] = \Illuminate\Support\Str::slug($request->name);

            // 4. Cria o registro
            $company = \App\Models\Company::create($data);

            Log::info('Empresa cadastrada com sucesso!');
            
            return redirect()->route('companies.show', $company->slug)
                ->with('success', 'Empresa cadastrada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao cadastrar empresa.');
        }
    }

    // Carregar o formulário editar
    public function edit(Company $company)
    {
        Log::info('Acesso a página de editar a Empresa!');
        return view('companies.edit', ['menu' => 'companies', 'company' => $company]);
    }

    // Atualizar registro
    public function update(CompanyRequest $request, $slug)
    {
        try {
            // Busca a empresa
            $company = \App\Models\Company::where('slug', $slug)->firstOrFail();

            // Caminho relativo (como fica salvo no banco)
            $folderName = 'uploads/imgCompanies';
            
            // --- A CORREÇÃO ESTÁ AQUI ---
            $destinationPath = public_path($folderName);

            $dbPath = $company->soon; 

            if ($request->hasFile('soon')) {
                // 1. Apagar imagem antiga
                if ($company->soon && File::exists(public_path($company->soon))) {
                    File::delete(public_path($company->soon));
                }

                // 2. Criar a pasta se não existir
                if (!File::exists($destinationPath)) {
                    // Cria a pasta recursivamente (uploads e depois imgCompanies)
                    File::makeDirectory($destinationPath, 0755, true, true);
                }


                $imageFile = $request->file('soon');
                $nameArquivo = uniqid() . '-soon.' . $imageFile->getClientOriginalExtension();
                $imageFile->move($destinationPath, $nameArquivo);
                
                // Define o caminho para o banco
                $dbPath = $folderName . '/' . $nameArquivo;
            }

            // Atualiza no Banco
            $company->update([
                'name' => $request->name,
                'slug' => \Illuminate\Support\Str::slug($request->name),
                'soon' => $dbPath,
                'link' => $request->link,
            ]);

            Log::info('Empresa atualizada. Caminho da imagem: ' . $destinationPath . '/' . ($nameArquivo ?? 'sem-mudanca'));

            return redirect()->route('companies.show', $company->slug)
                ->with('success', 'Empresa editada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao editar: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro: ' . $e->getMessage());
        }
    }
    
    public function destroy(Company $company)
    {
        try {
            
            if (!empty($company->soon)) {
                
                $filePath = public_path($company->soon);
    
                if (File::exists($filePath)) {
                    File::delete($filePath);
    
                    Log::info('Imagem da empresa apagada.', [
                        'company_id' => $company->id,
                        'file' => $filePath
                    ]);
                } else {
                    Log::warning('Imagem da empresa não encontrada.', [
                        'company_id' => $company->id,
                        'file' => $filePath
                    ]);
                }
            }
    
            $company->delete();
    
            Log::info('Empresa apagada com sucesso!', [
                'company_id' => $company->id
            ]);
    
            return redirect()
                ->route('companies.index')
                ->with('success', 'Empresa apagada com sucesso!');
    
        } catch (\Exception $e) {
            Log::error('Erro ao apagar empresa.', [
                'error' => $e->getMessage()
            ]);
    
            return back()->with('error', 'Erro ao apagar empresa.');
        }
    }

}
