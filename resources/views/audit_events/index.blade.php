@extends('layouts.admin')

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Relatório de Auditoria</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <span>Auditoria</span>
        </nav>
    </div>
</div>

<div class="content-box">
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total de Eventos -->
    <div class="bg-white backdrop-blur rounded-lg shadow-sm p-6 border">
        <div class="flex items-center">
            <div class="p-2 rounded-lg bg-red-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 text-red-600" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25m8.25-9V16.5a2.25 2.25 0 002.25 2.25H18m-9-4.5h10.5a1.5 1.5 0 011.5 1.5v2.25m-12-9h12a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H6a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-300">Total de Eventos</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $auditEvents->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Eventos Hoje -->
    <div class="bg-white backdrop-blur rounded-lg shadow-sm p-6 border">
        <div class="flex items-center">
            <div class="p-2 rounded-lg bg-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-300">Hoje</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $todayCount }}</p>
            </div>
        </div>
    </div>

    <!-- Usuários Únicos -->
    <div class="bg-white backdrop-blur rounded-lg shadow-sm p-6 border">
        <div class="flex items-center">
            <div class="p-2 rounded-lg bg-warning-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-warning-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-300">Usuários Únicos</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $uniqueUsers }}</p>
            </div>
        </div>
    </div>

    <!-- Modelos Auditados -->
    <div class="bg-white backdrop-blur rounded-lg shadow-sm p-6 border">
        <div class="flex items-center">
            <div class="p-2 rounded-lg bg-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-warning">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25m8.25-9A2.25 2.25 0 0016.5 3.75H18A2.25 2.25 0 0120.25 6v2.25M3.75 18A2.25 2.25 0 016 15.75h2.25a2.25 2.25 0 012.25 2.25v2.25m8.25-9v9m-8.25-9v9m-9 0h18" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-slate-300">Modelos</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $uniqueModels }}</p>
            </div>
        </div>
    </div>
</div>

    <div class="content-box-header">
        <h3 class="content-box-title">Listar Eventos</h3>
        <div class="content-box-btn">

            @can('generate-pdf-audit-events')
            <a href="{{ route('audit-events.generate-pdf-events', request()->query()) }}" class="btn-warning align-icon-btn">
                <!-- Ícone document (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>PDF</span>
            </a>
            @endcan

            @can('generate-csv-audit-events')
            <a href="{{ route('audit-events.generate-csv-events', request()->query()) }}" class="btn-success align-icon-btn">
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

    <!-- Formulário de Pesquisa -->
    <div class="content-box-header">
        <form class="form-search" method="GET" action="{{ route('audit-events.index') }}">
            <input type="text" name="event" class="form-input" placeholder="Evento (created, updated, deleted, accessed)" value="{{ request('event') }}">

            <input type="text" name="auditable_type" class="form-input" placeholder="Model (ex: App\Models\Product)" value="{{ request('auditable_type') }}">

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

                <a href="{{ route('audit-events.index') }}" class="btn-warning flex items-center space-x-1">
                    <!-- Ícone trash -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Limpar</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Gráficos -->
    <div class="flex flex-wrap justify-between gap-4 w-full content-box-btn">
        <div class="flex-1 min-w-[300px]">
            <canvas id="chartEvents" class="w-full h-72"></canvas>
        </div>
        <div class="flex-1 min-w-[300px]">
            <canvas id="chartDates" class="w-full h-72"></canvas>
        </div>
    </div>

    <!-- Tabela -->
    <div class="table-container mt-6">
        <table class="table">
            <thead>
                <tr class="table-row-header">
                    <th class="table-header">ID</th>
                    <th class="table-header">Evento</th>
                    <th class="table-header">Modelo</th>
                    <th class="table-header">Usuário</th>
                    <th class="table-header hidden lg:table-cell">IP</th>
                    <th class="table-header hidden lg:table-cell">Data</th>
                    <th class="table-header center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditEvents as $audit)
                <tr class="table-row-body">
                    <td class="table-body">{{ $audit->id }}</td>
                    <td class="table-body">{{ $audit->event }}</td>
                    <td class="table-body">{{ class_basename($audit->auditable_type) }}</td>
                    <td class="table-body">{{ $audit->user_id ?? 'Sistema' }}</td>
                    <td class="table-body hidden lg:table-cell">{{ $audit->ip_address }}</td>
                    <td class="table-body hidden lg:table-cell">{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                    <td class="table-actions">
                        <div class="table-actions-align">
                            @can('show-audit-event')
                            <a href="{{ route('audit-events.show', $audit->id) }}" class="btn-primary align-icon-btn">
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
                <tr>
                    <td colspan="7" class="table-body text-center">
                        <div class="alert-warning">
                            Nenhum evento encontrado!
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-4">
        {{ $auditEvents->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Gráfico por Evento
    new Chart(document.getElementById('chartEvents'), {
        type: 'bar',
        data: {
            labels: @json($labelsEvents),
            datasets: [{
                label: 'Eventos',
                data: @json($dataEvents),
                backgroundColor: 'rgba(251, 44, 54, 0.7)',
                borderColor: 'rgba(251, 44, 54, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico últimos 7 dias
    new Chart(document.getElementById('chartDates'), {
        type: 'line',
        data: {
            labels: @json($labelsDates),
            datasets: [{
                label: 'Acessos (últimos 7 dias)',
                data: @json($dataDates),
                backgroundColor: 'rgba(251, 44, 54, 0.2)',
                borderColor: 'rgba(251, 44, 54, 1)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'rgba(251, 44, 54, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection