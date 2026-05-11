@extends('layouts.admin')

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Relatório de Cliques</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <span>Relatório de Cliques</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Listar</h3>
        <div class="content-box-btn">

            @can('generate-pdf-logs')
            <a href="{{ url('logs/generate-pdf/logs') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn-warning align-icon-btn">
                <!-- Ícone document (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>PDF</span>
            </a>
            @endcan

            @can('generate-csv-logs')
            <a href="{{ url('logs/generate-csv/logs') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn-success align-icon-btn">
                <!-- Ícone document (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>CSV</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />

    <!-- Início Formulário de Pesquisa -->

    <div class="content-box-header">
        <form class="form-search" method="GET" action="{{ route('click-events.index') }}">
            {{-- Filtrar pelo tipo de acesso (action) --}}
            <input type="text" name="action" class="form-input" placeholder="Digite o tipo de acesso" value="{{ request('action') }}">

            {{-- Filtrar por IP --}}
           <!-- <input type="text" name="ip" class="form-input" placeholder="Digite o IP" value="{{-- request('ip') --}}"> -->

            {{-- Filtrar por Produto --}}
            <select name="product_id" class="form-input">
                <option value="">Selecione o Produto</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->title }}
                </option>
                @endforeach
            </select>

            {{-- Filtrar por Cupom --}}
            <select name="coupon_id" class="form-input">
                <option value="">Selecione o Cupom</option>
                @foreach($coupons as $coupon)
                <option value="{{ $coupon->id }}" {{ request('coupon_id') == $coupon->id ? 'selected' : '' }}>
                    {{ $coupon->code }}
                </option>
                @endforeach
            </select>

            {{-- Intervalo de datas --}}
            <input type="datetime-local" name="start_date" class="form-input" value="{{ request('start_date') }}">
            <input type="datetime-local" name="end_date" class="form-input" value="{{ request('end_date') }}">

            <div class="flex gap-1 mt-2">
                <button type="submit" class="btn-primary flex items-center space-x-1">
                    <!-- Ícone magnifying-glass -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span>Pesquisar</span>
                </button>

                <a href="{{ route('click-events.index') }}" class="btn-warning flex items-center space-x-1">
                    <!-- Ícone trash -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Limpar</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Fim Formulário de Pesquisa -->

    <!-- Gráficos-->
    <div class="flex flex-wrap justify-between gap-4 w-full content-box-btn">
        <!-- Gráfico por Ação -->
        <div class="flex-1 min-w-[300px]">
            <canvas id="chartActions" class="w-full h-72"></canvas>
        </div>

        <!-- Gráfico últimos 7 dias -->
        <div class="flex-1 min-w-[300px]">
            <canvas id="chartDates" class="w-full h-72"></canvas>
        </div>
    </div>

    <!-- Lista de registros de logs-->
    <div class="table-container mt-6">
        <table class="table">
            <thead>
                <tr class="table-row-header">
                    <th class="table-header">Códigos</th>
                    <th class="table-header">Descrição</th>
                    <th class="table-header">Tipo de acesso</th>
                    <th class="table-header hidden lg:table-cell">Agents</th>
                    <th class="table-header hidden lg:table-cell">Date</th>
                    <th class="table-header center">Ações</th>
                </tr>
            </thead>
            <tbody>
                {{-- Imprimir os registros --}}
                @forelse ($logs as $log)
                <tr class="table-row-body">
                    <td class="table-body">{{ $log->id }}</td>
                    <td class="table-body">{{ $log->action . '-' . $log->product_id }} : {{ Str::limit(optional($log->product)->title ?? '-', 30) }}</td>
                    <td class="table-body">{{ $log->source }}</td>
                    <td class="table-body hidden lg:table-cell">{{ Str::limit($log->user_agent ?? '-', 20) }}</td>
                    <td class="table-body hidden lg:table-cell">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td class="table-actions">
                        <div class="table-actions-align">
                            @can('show-log')
                            <a href="{{ route('click-events.show', ['clickEvent' => $log->id]) }}" class="btn-primary align-icon-btn">
                                <!-- Ícone eye (Heroicons) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span>Visualizar</span>
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>

                @empty
                <div class="alert-warning">
                    Nenhum registro encontrado!
                </div>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico por Ação
        new Chart(document.getElementById('chartActions'), {
            type: 'bar'
            , data: {
                labels: @json($labelsActions)
                , datasets: [{
                    label: 'Ações'
                    , data: @json($dataActions)
                    , backgroundColor: 'rgba(251, 44, 54, 0.7)'
                    , borderColor: 'rgba(251, 44, 54, 1)'
                    , borderWidth: 1
                }]
            }
            , options: {
                responsive: true
                , scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico últimos 7 dias
        new Chart(document.getElementById('chartDates'), {
            type: 'line'
            , data: {
                labels: @json($labelsDates)
                , datasets: [{
                    label: 'Acessos (últimos 7 dias)'
                    , data: @json($dataDates)
                    , backgroundColor: 'rgba(251, 44, 54, 0.2)'
                    , borderColor: 'rgba(251, 44, 54, 1)'
                    , tension: 0.3
                    , fill: true
                    , pointBackgroundColor: 'rgba(251, 44, 54, 1)'
                    , borderWidth: 2
                }]
            }
            , options: {
                responsive: true
                , scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

</script>
@endsection
