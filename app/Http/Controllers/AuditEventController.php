<?php

namespace App\Http\Controllers;

use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuditEventController extends Controller
{
     /**
     * 📊 Lista auditorias + métricas
     */
    public function index(Request $request)
    {
        // 🔒 Restringe acesso apenas a você
        $allowedUserId = 1; // 👈 Substitua pelo seu user_id real
        if (Auth::id() !== $allowedUserId) {
            abort(403, 'Acesso negado.');
        }
    
        $query = AuditEvent::query()->with('user');
    
        /*
        |--------------------------------------------------------------------------
        | 🔍 FILTROS
        |--------------------------------------------------------------------------
        */
    
        if ($request->filled('event')) {
            $query->where('event', 'like', '%' . $request->event . '%');
        }
    
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
    
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', 'like', '%' . $request->auditable_type . '%');
        }
    
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }
    
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
    
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }
    
        /*
        |--------------------------------------------------------------------------
        | 📄 LISTAGEM
        |--------------------------------------------------------------------------
        */
    
        $auditEvents = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();
    
        /*
        |--------------------------------------------------------------------------
        | 📊 MÉTRICAS (RESPEITAM FILTROS)
        |--------------------------------------------------------------------------
        */
    
        // Clona a query base para evitar conflito
        $metricsQuery = clone $query;
    
        // Eventos por tipo
        $eventsData = $metricsQuery
            ->select('event', DB::raw('count(*) as total'))
            ->groupBy('event')
            ->orderByDesc('total')
            ->get();
    
        $labelsEvents = $eventsData->pluck('event');
        $dataEvents   = $eventsData->pluck('total');
    
        // Últimos 7 dias (com filtros)
        $datesData = (clone $query)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        $labelsDates = $datesData->pluck('date');
        $dataDates   = $datesData->pluck('total');
        
        /*
        |--------------------------------------------------------------------------
        | 📊 MÉTRICAS ADICIONAIS (RESPEITAM FILTROS)
        |--------------------------------------------------------------------------
        */
        
        // Eventos HOJE
        $todayCount = (clone $query)->whereDate('created_at', today())->count();
        
        // Usuários únicos
        $uniqueUsers = (clone $query)->whereNotNull('user_id')->distinct('user_id')->count();
        
        // Modelos auditados únicos
        $uniqueModels = (clone $query)->distinct('auditable_type')->count();

    
        return view('audit_events.index', [
            'menu'           => 'audit-events',
            'auditEvents'    => $auditEvents,
            'labelsEvents'   => $labelsEvents,
            'dataEvents'     => $dataEvents,
            'labelsDates'    => $labelsDates,
            'dataDates'      => $dataDates,
            'users'          => User::orderBy('name')->get(),
        
            // Métricas adicionais
            'todayCount'     => $todayCount,
            'uniqueUsers'    => $uniqueUsers,
            'uniqueModels'   => $uniqueModels,
        ]);
        
    }
    
    /**
     * 👁️ Visualizar auditoria
     */
    public function show(AuditEvent $auditEvent)
    {
        return view('audit_events.show', [
            'menu'       => 'audit-events',
            'auditEvent' => $auditEvent->load('user'),
        ]);
    }
}
