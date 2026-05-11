<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Api\TelegramWebhookController;

Route::get('/teste', function () {
    return ['status' => 'Tudo ok!'];
});

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);

Route::post('/autolink-receive', [TelegramWebhookController::class, 'receiveFromTelegram']);

/*
|--------------------------------------------------------------------------
| Rotas de Manutenção (Comentar em Produção após uso)
|--------------------------------------------------------------------------
*/

/*

https://achadosmulhersabia.com.br/api/fix-logs-table


Route::get('/fix-logs-table', function () {
    try {
        if (Schema::hasTable('logs')) {
            if (Schema::hasTable('click_events')) {
                return response()->json(['message' => 'Tabela click_events já existe.'], 409);
            }
            Schema::rename('logs', 'click_events');
            return response()->json(['message' => '✅ Tabela renomeada com sucesso!']);
        } else {
            return response()->json(['error' => 'Tabela logs não encontrada.'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

/*

/*
Route::get('/consertar-tabela-empresas', function () {
    try {
        if (Schema::hasTable('companys')) {
            if (Schema::hasTable('companies')) {
                return "<h3 style='color:orange'>Atenção: A tabela 'companies' já existe.</h3>";
            }
            Schema::rename('companys', 'companies');
            return "<h3 style='color:green'>✅ Sucesso! Tabela renomeada!</h3>";
        } else {
            return "<h3 style='color:red'>Erro: Tabela 'companys' não encontrada.</h3>";
        }
    } catch (\Exception $e) {
        return "<h3 style='color:red'>❌ Erro: " . $e->getMessage() . "</h3>";
    }
});

Route::get('/limpar-tudo', function() {
   Artisan::call('cache:clear');
   Artisan::call('view:clear');
   Artisan::call('config:clear');
   return "Cache limpo com sucesso!";
});
*/
