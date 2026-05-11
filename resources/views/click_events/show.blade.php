@extends('layouts.admin')

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Show do Clique</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('click-events.index') }}" class="breadcrumb-link">Eventos de Clique</a>
            <span>/</span>
            <span>Registro</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Registro: nº {{ $clickEvent->id }}</h3>
        <div class="content-box-btn">

            @can('index-clickEvent')
            <a href="{{ route('click-events.index') }}" class="btn-info align-icon-btn">
                <!-- Ícone queue-list (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan

            @can('generate-pdf-clickEvent')
            <a href="{{-- route('click-events.generate-pdf-click-event', $clickEvent->id) --}}"class="btn-warning align-icon-btn">

                <!-- Ícone document (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>PDF</span>
            </a>
            @endcan

            @can('destroy-clickEvent')
            <form id="delete-form-{{-- $clickEvent->id }}" action="{{ route('clickEvents.destroy', ['clickEvent' => $clickEvent->id]) --}}" method="POST">
                @csrf
                @method('delete')

                <button type="button" onclick="confirmDelete('{{-- $clickEvent->id --}}')" class="btn-danger flex items-center space-x-1">
                    <!-- Ícone trash (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Apagar</span>
                </button>
            </form>
            @endcan
        </div>
    </div>

    <x-alert />
    <div class="detail-box">

        <div class="mb-1">
            <span class="title-detail-content">Tipo de Acesso: </span>
            <span class="detail-content">{{ $clickEvent->action }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Produto: </span>
            <span class="detail-content">{{ $clickEvent->product ? $clickEvent->product->title : '-' }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Cupom: </span>
            <span class="detail-content">{{ $clickEvent->coupon ? $clickEvent->coupon->code : '-' }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">IP: </span>
            <span class="detail-content">{{ $clickEvent->ip ?? '-' }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">User Agent: </span>
            <span class="detail-content">{{ $clickEvent->user_agent ?? '-' }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Cadastrado: </span>
            <span class="detail-content">{{ \Carbon\Carbon::parse($clickEvent->created_at)->format('d/m/Y H:i:s') }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Editado: </span>
            <span class="detail-content">{{ \Carbon\Carbon::parse($clickEvent->updated_at)->format('d/m/Y H:i:s') }}</span>
        </div>

    </div>
</div>
@endsection
