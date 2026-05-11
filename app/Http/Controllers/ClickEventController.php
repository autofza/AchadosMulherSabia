<?php

namespace App\Http\Controllers;

use App\Models\ClickEvent;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Str;

class ClickEventController extends Controller
{
    /**
     * 📊 Dashboard + listagem de eventos de clique
     */
    public function index_r00(Request $request)
    {
        // ---------- Gráfico 1: total por ação ----------
        $eventsByAction = ClickEvent::selectRaw('action, COUNT(*) as total')
            ->groupBy('action')
            ->orderByDesc('total')
            ->get();

        $labelsActions = $eventsByAction->pluck('action');
        $dataActions   = $eventsByAction->pluck('total');

        // ---------- Gráfico 2: últimos 7 dias ----------
        $dates = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));

        $eventsByDate = ClickEvent::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $labelsDates = $dates->map(fn ($date) => Carbon::parse($date)->format('d/m/Y'));
        $dataDates   = $dates->map(fn ($date) => $eventsByDate[$date] ?? 0);

        // ---------- Lista com filtros ----------
        $eventsQuery = ClickEvent::with(['product', 'coupon', 'company'])
            ->when($request->filled('action'), fn ($q) => $q->where('action', 'like', "%{$request->action}%"))
            ->when($request->filled('source'), fn ($q) => $q->where('source', $request->source))
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->product_id))
            ->when($request->filled('company_id'), fn ($q) => $q->where('company_id', $request->company_id))
            ->when($request->filled('ip'), fn ($q) => $q->where('ip', 'like', "%{$request->ip}%"))
            ->when($request->filled('start_date'), fn ($q) => $q->where('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->where('created_at', '<=', $request->end_date))
            ->orderByDesc('created_at');

        $events = $eventsQuery->paginate(10)->withQueryString();

        $products  = Product::orderBy('title')->get();
        $companies = Company::orderBy('name')->get();
        $coupons   = Coupon::orderBy('code')->get();

        Log::info('📊 Relatório de ClickEvents acessado', ['user_id' => Auth::id()]);

        return view('click_events.index', compact(
            'labelsActions',
            'dataActions',
            'labelsDates',
            'dataDates',
            'events',
            'products',
            'companies',
            'coupons'
        ));
    }

    public function index(Request $request)
    {
        // ---------- Gráfico por ação ----------
        $eventsByAction = ClickEvent::selectRaw('action, COUNT(*) as total')
            ->groupBy('action')
            ->orderByDesc('total')
            ->get();
    
        $labelsActions = $eventsByAction->pluck('action');
        $dataActions   = $eventsByAction->pluck('total');
    
        // ---------- Gráfico últimos 7 dias ----------
        $dates = collect(range(6, 0))
            ->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));
    
        $eventsByDate = ClickEvent::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date');
    
        $labelsDates = $dates->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d/m/Y'));
        $dataDates   = $dates->map(fn ($d) => $eventsByDate[$d] ?? 0);
    
        // ---------- LISTAGEM (ESSENCIAL) ----------
        $logs = ClickEvent::with(['product', 'coupon', 'company'])
            ->when($request->filled('action'),
                fn ($q) => $q->where('action', 'like', "%{$request->action}%"))
            ->when($request->filled('product_id'),
                fn ($q) => $q->where('product_id', $request->product_id))
            ->when($request->filled('coupon_id'),
                fn ($q) => $q->where('coupon_id', $request->coupon_id))
            ->when($request->filled('start_date'),
                fn ($q) => $q->where('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'),
                fn ($q) => $q->where('created_at', '<=', $request->end_date))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();
    
        // ---------- SELECTS ----------
        $products = Product::orderBy('title')->get();
        $coupons  = Coupon::orderBy('code')->get();
    
        return view('click_events.index', [
            'menu'          => 'click-events',
            'logs'          => $logs,           // 🔴 ESSENCIAL
            'labelsActions' => $labelsActions,
            'dataActions'   => $dataActions,
            'labelsDates'   => $labelsDates,
            'dataDates'     => $dataDates,
            'products'      => $products,
            'coupons'       => $coupons,
        ]);
    }

    /**
     * 🔍 Visualizar evento individual
     */
    public function show(ClickEvent $clickEvent)
    {
        $previous = ClickEvent::where('id', '<', $clickEvent->id)->orderByDesc('id')->first();
        $next     = ClickEvent::where('id', '>', $clickEvent->id)->orderBy('id')->first();

        return view('click_events.show', compact('clickEvent', 'previous', 'next'));
    }

    /**
     * 📄 PDF de um único evento
     */
    public function generatePdfEvent(ClickEvent $clickEvent)
    {
        try {
            $pdf = Pdf::loadView('click_events.generate_pdf_event', [
                'clickEvent' => $clickEvent
            ])->setPaper('a4', 'portrait');

            return $pdf->download('click_event_' . $clickEvent->id . '.pdf');
        } catch (Exception $e) {
            Log::notice('❌ PDF ClickEvent não gerado', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'PDF não pôde ser gerado.');
        }
    }

    /**
     * 📄 PDF da lista filtrada
     */
    public function generatePdfEvents(Request $request)
    {
        try {
            $events = ClickEvent::with(['product', 'coupon', 'company'])
                ->when($request->filled('action'), fn ($q) => $q->where('action', 'like', "%{$request->action}%"))
                ->when($request->filled('start_date'), fn ($q) => $q->where('created_at', '>=', Carbon::parse($request->start_date)))
                ->when($request->filled('end_date'), fn ($q) => $q->where('created_at', '<=', Carbon::parse($request->end_date)))
                ->orderByDesc('created_at')
                ->get();

            if ($events->count() > 500) {
                return back()->with('error', 'Limite de 500 registros para PDF.');
            }

            $pdf = Pdf::loadView('click_events.generate_pdf_events', [
                'events' => $events
            ])->setPaper('a4', 'portrait');

            return $pdf->download('click_events.pdf');
        } catch (Exception $e) {
            Log::notice('❌ PDF lista ClickEvents não gerado', ['error' => $e->getMessage()]);
            return back()->with('error', 'PDF não gerado.');
        }
    }

    /**
     * 📑 CSV dos eventos
     */
    public function generateCsvEvents(Request $request)
    {
        try {
            $events = ClickEvent::with(['product', 'coupon', 'company'])
                ->orderByDesc('created_at')
                ->get();

            if ($events->count() > 500) {
                return back()->with('error', 'Limite de 500 registros para CSV.');
            }

            $file = tempnam(sys_get_temp_dir(), 'csv_' . Str::ulid());
            $open = fopen($file, 'w');

            fputcsv($open, ['ID', 'Ação', 'Produto', 'Empresa', 'Cupom', 'Origem', 'IP', 'User Agent', 'Data'], ';');

            foreach ($events as $event) {
                fputcsv($open, [
                    $event->id,
                    $event->action,
                    $event->product?->title ?? '-',
                    $event->company?->name ?? '-',
                    $event->coupon?->code ?? '-',
                    $event->source,
                    $event->ip,
                    $event->user_agent,
                    $event->created_at->format('d/m/Y H:i:s'),
                ], ';');
            }

            fclose($open);

            return response()->download($file, 'click_events.csv');
        } catch (Exception $e) {
            Log::notice('❌ CSV ClickEvents não gerado', ['error' => $e->getMessage()]);
            return back()->with('error', 'CSV não gerado.');
        }
    }
}
