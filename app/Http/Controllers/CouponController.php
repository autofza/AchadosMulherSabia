<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Models\Company;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    // Lista todos os cupons
    public function index()
    {
        // Recuperar os registros do banco dados
        $coupons = Coupon::orderBy('updated_at', 'DESC')->paginate(10);

        // Salvar log
        Log::info('Listar os cupons.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('coupons.index', ['menu' => 'coupons', 'coupons' => $coupons]);
    }

    // Formulário para criar novo cupom
    public function create()
    {
        $companys = Company::orderBy('name')->get();

        return view('coupons.create', ['menu' => 'coupons', 'companys' => $companys]);
    }

    // Salva o cupom
    public function store_Roo(CouponRequest $request)
    {
        try {
            $coupon = Coupon::create([
                'code' => $request->code,
                'value' => $request->value,
                'company_id' => $request->company_id, // ← salva o relacionamento
                'active' => $request->active ?? false, // padrão: inativo
            ]);

            // Log de sucesso
            Log::info('Cupom cadastrado com sucesso.', [
                'coupon_id' => $coupon->id,
                'company_id' => $coupon->company_id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('coupons.show', $coupon)
                ->with('success', 'Cupom cadastrado com sucesso!');
        } catch (\Exception $e) {
            // Log do erro
            Log::error('Falha ao cadastrar cupom.', [
                'error' => $e->getMessage(),
                'request_data' => $request->except('password'), // evita logs de senhas
                'action_user_id' => Auth::id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível cadastrar o cupom. Tente novamente.');
        }
    }

    public function store(CouponRequest $request)
    {
        try {
            $coupon = Coupon::create([
                'code' => $request->code,
                'value' => $request->value,
                'company_id' => $request->company_id,
                'active' => $request->active ?? false,
                'link' => $request->link, // adicionado
            ]);

            Log::info('Cupom cadastrado com sucesso.', [
                'coupon_id' => $coupon->id,
                'company_id' => $coupon->company_id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('coupons.show', $coupon)
                ->with('success', 'Cupom cadastrado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Falha ao cadastrar cupom.', [
                'error' => $e->getMessage(),
                'request_data' => $request->except('password'),
                'action_user_id' => Auth::id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível cadastrar o cupom. Tente novamente.');
        }
    }

    // Mostra detalhes de um cupom
    public function show(Coupon $coupon)
    {
        // Salvar log
        Log::info('Visualizar o cupom.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('coupons.show', ['menu' => 'coupons', 'coupon' => $coupon]);
    }

    // Formulário para editar cupom
    public function edit(Coupon $coupon)
    {

        $companys = Company::orderBy('name')->get();

        Log::info('Alterar o cupom.', ['coupon_id' => $coupon->id]);

        return view('coupons.edit', ['menu' => 'coupons', 'coupon' => $coupon, 'companys' => $companys,]);
    }

    // Atualiza o cupom
    public function update(CouponRequest $request, Coupon $coupon)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Editar as informações do registro no banco de dados
            $coupon->update($request->validated());

            // Salvar log
            Log::info('Cupom editado.', ['coupon_id' => $coupon->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('coupons.show', ['coupon' => $coupon->id])->with('success', 'Cupom editado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Cupom não editado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Cupom não editado!');
        }
    }

    // Deleta o cupom
    public function destroy(Coupon $coupon)
    {
        // Capturar possíveis exceções durante a execução.
        try {

            // Excluir o registro do banco de dados
            $coupon->delete();

            // Salvar log
            Log::info('Cupom apagado.', ['coupon_id' => $coupon->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('coupons.index')->with('success', 'Cupom apagado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Cupom não apagado.', ['error' => $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Cupom não apagado!');
        }
    }

    /**
     * Lista todos os cupons de uma empresa específica.
     */
    public function listByCompany($companyId)
    {
        // Carrega a empresa com seus cupons
        $company = Company::findOrFail($companyId);

        $coupons = Coupon::with('company')->where('company_id', $companyId)->orderBy('updated_at', 'DESC')->paginate(10);

        // Salvar log
        Log::info('Lista de cupons da loja.', [$companyId, 'action_user_id' => Auth::id()]);

        return view('coupons.byCompany', compact('coupons', 'company'));
    }

    /**
     * Mudar o Status do Cupom de Atino p/ Inativo.
     */
    public function toggle(Coupon $coupon)
    {
        try {
            // Inverte o status atual
            $coupon->active = !$coupon->active;
            $coupon->save();

            Log::info('Status do cupom alterado.', [
                'coupon_id' => $coupon->id,
                'new_status' => $coupon->active,
                'action_user_id' => Auth::id()
            ]);

            return redirect()->route('coupons.index')
                ->with('success', 'Status do cupom atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Falha ao alterar status do cupom.', [
                'error' => $e->getMessage(),
                'coupon_id' => $coupon->id,
                'action_user_id' => Auth::id()
            ]);

            return back()->with('error', 'Não foi possível atualizar o status do cupom.');
        }
    }
}
