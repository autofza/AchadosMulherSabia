<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterUserRequest;
use App\Models\User;
use App\Models\AuditEvent;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // Login
    public function index()
    {
        return view('auth.login');
    }

    // Validar os dados do usuário no login
    public function loginProcess(AuthLoginRequest $request)
    {
        try {
            $authenticated = Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);

            if (!$authenticated) {
                // 🔹 Log Laravel
                Log::notice('E-mail ou senha inválido!', ['email' => $request->email]);

                // 🔐 AuditEvent (tentativa inválida)
                AuditEvent::log(
                    event: 'login_failed',
                    model: null,
                    old: [],
                    new: ['email' => $request->email],
                    tags: ['auth', 'login']
                );

                return back()->withInput()->with('error', 'E-mail ou senha inválido!');
            }

            // 🔹 Log Laravel
            Log::info('Login', ['action_user_id' => Auth::id()]);

            // 🔐 AuditEvent (login sucesso)
            AuditEvent::log(
                event: 'login',
                model: auth()->user(),
                tags: ['auth', 'login']
            );

            return redirect()->route('dashboard.index');
        } catch (Exception $e) {

            // 🔹 Log Laravel
            Log::notice('Dados do login incorreto.', ['error' => $e->getMessage()]);

            // 🔐 AuditEvent (erro inesperado)
            AuditEvent::log(
                event: 'login_error',
                model: null,
                new: ['error' => $e->getMessage()],
                tags: ['auth', 'exception']
            );

            return back()->withInput()->with('error', 'E-mail ou senha inválido!');
        }
    }

    // Deslogar o usuário
    public function logout()
    {
        // 🔐 AuditEvent (logout) — antes de deslogar
        AuditEvent::log(
            event: 'logout',
            model: auth()->user(),
            tags: ['auth', 'logout']
        );

        // 🔹 Log Laravel
        Log::notice('Logout.', ['action_user_id' => Auth::id()]);

        Auth::logout();

        return redirect()->route('login')->with('success', 'Deslogado com sucesso!');
    }

    // Formulário cadastrar novo usuário
    public function create()
    {
        return view('auth.register');
    }

    // Cadastrar novo usuário
    public function store(AuthRegisterUserRequest $request)
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            if (Role::where('name', 'Colaborador')->exists()) {
                $user->assignRole('Colaborador');
            }

            // 🔹 Log Laravel
            Log::info('Usuário cadastrado.', ['user_id' => $user->id]);

            // 🔐 AuditEvent (registro)
            AuditEvent::log(
                event: 'user_registered',
                model: $user,
                new: $user->only(['name', 'email']),
                tags: ['auth', 'register']
            );

            return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso!');
        } catch (Exception $e) {

            // 🔹 Log Laravel
            Log::notice('Usuário não cadastrado.', ['error' => $e->getMessage()]);

            // 🔐 AuditEvent (erro cadastro)
            AuditEvent::log(
                event: 'user_register_error',
                model: null,
                new: ['error' => $e->getMessage()],
                tags: ['auth', 'exception']
            );

            return back()->withInput()->with('error', 'Cadastro não realizado!');
        }
    }
}
