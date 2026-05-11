<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // Página inicial do administrativo
    public function index()
    {

        // Capturar possíveis exceções durante a execução.
        try {
            // Dados estáticos para o gráfico
            // $labels = ['Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro', 'Janeiro', 'Fevereiro', 'Março', 'Abril'];
            // $data = [100, 120, 110, 150, 140, 180, 120, 100, 110, 180, 190, 220];

            // Dados dinâmicos para o gráfico
            // Início do período: 12 meses atrás
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            // Buscar dados do banco agrupados por mês
            $userByMonth = User::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            // Criar arrays
            $labels = [];
            $data = [];

            // Iterar os últimos 12 meses
            for ($i = 0; $i < 12; $i++) {
                $month = $startDate->copy()->addMonth($i);
                $key = $month->format('Y-m');
                $labels[] = ucfirst($month->translatedFormat('F'));
                $data[] = $userByMonth->get($key, 0);
            }

            // Salvar log
            Log::notice('Dashboard.', ['action_user_id' => Auth::id()]);

            // Carregar a VIEW
            return view('dashboard.index', ['menu' => 'dashboard', 'labels' => $labels, 'data' => $data]);
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Dados para o gráfico não gerado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Dados para o gráfico não gerado!');
        }
    }
}
