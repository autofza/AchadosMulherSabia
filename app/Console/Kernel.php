<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define os comandos Artisan personalizados do app.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Agenda tarefas automáticas (cron jobs).
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $count = Product::deactivateExpired();
            if ($count > 0) {
                Log::info("🔕 {$count} produtos desativados automaticamente (expirados)");
            }
        })->hourly(); // executa a cada hora
    }
}
