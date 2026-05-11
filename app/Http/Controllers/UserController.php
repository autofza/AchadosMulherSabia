<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\AuditEvent;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{

    // Listar os usuários
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        // $users = User::where('id', 1000)->get();
        // $users = User::orderBy('id', 'DESC')->get();
        //$users = User::orderBy('id', 'DESC')->paginate(10);
        $users = User::when(
            $request->filled('name'),
            fn($query) =>
            $query->whereLike('name', '%' . $request->name .  '%')
        )
            ->when(
                $request->filled('email'),
                fn($query) =>
                $query->whereLike('email', '%' . $request->email .  '%')
            )
            ->when(
                $request->filled('start_date_registration'),
                fn($query) =>
                $query->where('created_at', '>=', Carbon::parse($request->start_date_registration))
            )
            ->when(
                $request->filled('end_date_registration'),
                fn($query) =>
                $query->where('created_at', '<=', Carbon::parse($request->end_date_registration))
            )
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        // Salvar log
        Log::info('Listar os usuários.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('users.index', [
            'menu' => 'users',
            'name' => $request->name,
            'email' => $request->email,
            'start_date_registration' => $request->start_date_registration,
            'end_date_registration' => $request->end_date_registration,
            'users' => $users
        ]);
    }

    // Visualizar os detalhes do usuário
    public function show(User $user)
    {

        // Salvar log
        Log::info('Visualizar o usuário.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('users.show', ['menu' => 'users', 'user' => $user]);
    }

    // Carregar o formulário cadastrar novo usuário
    public function create()
    {
        // Recuperar os papéis
        $roles = Role::pluck('name')->all();

        // Carregar a view 
        return view('users.create', ['menu' => 'users', 'roles' => $roles]);
    }

    // Cadastrar no banco de dados o novo usuário
    public function store(UserRequest $request)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Cadastrar no banco de dados na tabela usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Verificar se veio algum papel selecionado
            if ($request->filled('roles')) {
                // Verifica se todos os papéis existem (opcional, mas recomendado)
                $validRoles = Role::whereIn('name', $request->roles)->pluck('name')->toArray();

                // Atribui os papéis válidos ao usuário
                $user->syncRoles($validRoles); // syncRoles() vários papeís ou assignRole() se for apenas um
            }

            // Salvar log
            Log::info('Usuário cadastrado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('users.show', ['user' => $user->id])->with('success', 'Usuário cadastrado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Usuário não cadastrado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não cadastrado!');
        }
    }

    // Carregar o formulário editar usuário
    public function edit(User $user)
    {
        // Recuperar os papéis
        $roles = Role::pluck('name')->all();

        // Recuperar os papéis do usuário
        $userRoles = $user->roles->pluck('name')->toArray();

        // Carregar a view 
        return view('users.edit', [
            'menu' => 'users',
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }

    // Editar no banco de dados o usuário
    public function update(UserRequest $request, User $user)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Editar as informações do registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Se houver papéis enviados no request, sincroniza-os com o usuário
            if ($request->filled('roles')) {
                // Verifica se todos os papéis existem (opcional, mas recomendado)
                $validRoles = Role::whereIn('name', $request->roles)->pluck('name')->toArray();

                // Atribui os papéis válidos ao usuário
                $user->syncRoles($validRoles); // syncRoles() vários papeís ou assignRole() se for apenas um
            } else {
                // Se nenhum papel for enviado, remove todos os papéis do usuário
                $user->syncRoles([]);
            }

            // Salvar log
            Log::info('Usuário editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('users.show', ['user' => $user->id])->with('success', 'Usuário editado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Usuário não editado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não editado!');
        }
    }

    // Carregar o formulário editar senha do usuário
    public function editPassword(User $user)
    {
        // Carregar a view 
        return view('users.edit_password', ['user' => $user]);
    }

    // Editar no banco de dados a senha do usuário
    public function updatePassword(Request $request, User $user)
    {
        $request->validate(
            [
                'password' => 'required|confirmed|min:6',
            ],
            [
                'password.required' => "Campo senha é obrigatório!",
                'password.confirmed' => 'A confirmação da senha não corresponde!',
                'password.min' => "Senha com no mínimo :min caracteres!",
            ]
        );
    
        try {
            $user->update([
                'password' => $request->password,
            ]);
    
            // 🔹 Log Laravel
            Log::info('Senha do usuário editada.', [
                'user_id' => $user->id,
                'action_user_id' => Auth::id()
            ]);
    
            // 🔐 AuditEvent — troca de senha
            AuditEvent::log(
                event: 'password_changed',
                model: $user,
                old: [],
                new: [],
                tags: ['auth', 'password']
            );
    
            return redirect()
                ->route('users.show', ['user' => $user->id])
                ->with('success', 'Senha do usuário editada com sucesso!');
        } catch (Exception $e) {
    
            // 🔹 Log Laravel
            Log::notice('Senha do usuário não editada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);
    
            // 🔐 AuditEvent — erro na troca de senha
            AuditEvent::log(
                event: 'password_change_error',
                model: $user,
                new: ['error' => $e->getMessage()],
                tags: ['auth', 'password', 'exception']
            );
    
            return back()->withInput()->with('error', 'Senha do usuário não editada!');
        }
    }

    // Excluir o curso do banco de dados
    public function destroy(User $user)
    {
        // Capturar possíveis exceções durante a execução.
        try {

            // Excluir o registro do banco de dados
            $user->delete();

            // Salvar log
            Log::info('Usuário apagado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('users.index')->with('success', 'Usuário apagado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Usuário não editado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não apagado!');
        }
    }
 
    // Gerar PDF
    public function generatePdfUser(User $user)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Carregar a string com o HTML/conteúdo e determinar a orientação e o tamanho do arquivo
            $pdf = Pdf::loadView('users.generate_pdf_user', ['user' => $user])->setPaper('a4', 'portrait');

            // Fazer o download do arquivo
            return $pdf->download('view_user_' . $user->id . '.pdf');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('PDF dos dados do usuário não gerado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'PDF dos dados do usuário não gerado!');
        }
    }

    public function generatePdfUsers(Request $request)
    {
        // Capturar possíveis exceções durante a execução.
        try {

            // Recuperar os registros do banco dados
            $users = User::when(
                $request->filled('name'),
                fn($query) =>
                $query->whereLike('name', '%' . $request->name .  '%')
            )
                ->when(
                    $request->filled('email'),
                    fn($query) =>
                    $query->whereLike('email', '%' . $request->email .  '%')
                )
                ->when(
                    $request->filled('start_date_registration'),
                    fn($query) =>
                    $query->where('created_at', '>=', Carbon::parse($request->start_date_registration))
                )
                ->when(
                    $request->filled('end_date_registration'),
                    fn($query) =>
                    $query->where('created_at', '<=', Carbon::parse($request->end_date_registration))
                )
                ->orderBy('name')
                ->get();

            // Somar total de registros
            $totalRecords = $users->count('id');

            // Verificar se a quantidade de registros ultrapassa o limite para gerar PDF
            $numberRecordsAllowed = 500;
            if ($totalRecords > $numberRecordsAllowed) {
                // Salvar log
                Log::notice("Limite de registros ultrapassado para gerar PDF. O limite é de $numberRecordsAllowed registros.", ['action_user_id' => Auth::id()]);

                // Redirecionar o usuário, enviar a mensagem de erro
                return back()->withInput()->with('error', "Limite de registros ultrapassado para gerar PDF. O limite é de $numberRecordsAllowed registros!");
            }

            // Carregar a string com o HTML/conteúdo e determinar a orientação e o tamanho do arquivo
            $pdf = Pdf::loadView('users.generate_pdf_users', ['users' => $users])->setPaper('a4', 'portrait');

            // Fazer o download do arquivo
            return $pdf->download('list_users.pdf');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('PDF dos usuários não gerado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'PDF dos usuários não gerado!');
        }
    }

    public function generateCSVUsers(Request $request)
    {
        // Capturar possíveis exceções durante a execução.
        try {

            // Recuperar os registros do banco dados
            $users = User::when(
                $request->filled('name'),
                fn($query) =>
                $query->whereLike('name', '%' . $request->name .  '%')
            )
                ->when(
                    $request->filled('email'),
                    fn($query) =>
                    $query->whereLike('email', '%' . $request->email .  '%')
                )
                ->when(
                    $request->filled('start_date_registration'),
                    fn($query) =>
                    $query->where('created_at', '>=', Carbon::parse($request->start_date_registration))
                )
                ->when(
                    $request->filled('end_date_registration'),
                    fn($query) =>
                    $query->where('created_at', '<=', Carbon::parse($request->end_date_registration))
                )
                ->orderBy('name')
                ->get();

                // Somar total de registros
                $totalRecords = $users->count('id');
    
                // Verificar se a quantidade de registros ultrapassa o limite para gerar CSV
                $numberRecordsAllowed = 500;
                if ($totalRecords > $numberRecordsAllowed) {
                    // Salvar log
                    Log::notice("Limite de registros ultrapassado para gerar CSV. O limite é de $numberRecordsAllowed registros.", ['action_user_id' => Auth::id()]);
    
                    // Redirecionar o usuário, enviar a mensagem de erro
                    return back()->withInput()->with('error', "Limite de registros ultrapassado para gerar CSV. O limite é de $numberRecordsAllowed registros!");
                }

                // Criar o arquivo temporário
                $csvFileName = tempnam(sys_get_temp_dir(), 'csv_' . Str::ulid());

                // Abrir o arquivo na forma de escrita
                $openFile = fopen($csvFileName, 'w');

                // Criar o cabeçalho do Excel
                $header = ['id', 'Nome', 'E-mail', 'Data de Cadastrado'];

                // Escrever o cabeçalho no arquivo
                fputcsv($openFile, $header, ';');

                // Ler os registros recuperados do banco de dados
                foreach ($users as $user) {
                    // Criar o array com os dados da linha do Excel
                    $userArray = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s'),
                    ];

                    // Escrever o conteúdo no arquivo
                    fputcsv($openFile, $userArray, ';');
                }

                // Fechar o arquivo após a escrita
                fclose($openFile);

                // Realizar o download do arquivo
                return response()->download($csvFileName, 'list_users.csv');
                
        } catch (Exception $e) {

            // Salvar log
            Log::notice('CSV dos usuários não gerado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'CSV dos usuários não gerado!');
        }
    }
}
