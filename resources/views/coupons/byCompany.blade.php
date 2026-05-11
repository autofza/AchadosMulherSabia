@extends('layouts.admin')

@section('content')

<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Cupons da empresa: {{ $company->name }}</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <span>Cupons da {{ $company->name }}</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Litar</h3>
        <div class="content-box-btn">
            @can('index-coupon')
            <a href="{{ route('coupons.index') }}" class="btn-info align-icon-btn">
                <!-- Ícone queue-list (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan
            @can('create-coupon')
            <a href="{{ route('coupons.create') }}" class="btn-success flex items-center space-x-1">
                <!-- Ícone plus-circle (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Cadastrar</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />

    <div class="table-container mt-6">
        <table class="table">
            <thead>
                <tr class="table-row-header">
                    <th class="table-header">Códigos</th>
                    <th class="table-header">Descrição</th>
                    <th class="table-header">Valores</th>
                    <th class="table-header">Lojas</th>
                    <th class="table-header">Status</th>
                    <th class="table-header center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                <tr class="table-row-body">
                    <td class="table-body">{{ $coupon->id }}</td>
                    <td class="table-body">{{ $coupon->code }}</td>
                    <td class="table-body">{{ $coupon->value }}</td>
                    <td class="table-body">{{ $coupon->company?->name }}</td>
                    <td class="table-body">
                        @if ($coupon->active)
                        <!-- Ícone de check (verde) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500 hover:text-green-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        @else
                        <!-- Ícone de X (vermelho) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-red-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        @endif
                    </td>
                    <td class="table-actions">
                        <div class="table-actions-align">
                            @php
                            $isActive = $coupon->active ?? false;
                            @endphp

                            @if($isActive)
                            <a href="{{ route('coupons.byCompany', $coupon->company->id) }}" class="btn-success flex items-center space-x-1">
                                @else
                                <span class="btn-warning flex items-center space-x-1 opacity-50 cursor-not-allowed" aria-disabled="true">
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15.75h6m-6-3H15m-6-3h6m6-1.5A2.25 2.25 0 0 1 18.75 6h-13.5A2.25 2.25 0 0 1 3 8.25v7.5A2.25 2.25 0 0 1 5.25 18h13.5A2.25 2.25 0 0 1 21 15.75v-7.5Z" />
                                    </svg>
                                    <span>Ver os Cupons</span>
                                    @if($isActive)
                            </a>
                            @else
                            </span>
                            @endif
                            <!-- endcan -->

                            @can('show-coupon')
                            <a href="{{ route('coupons.show', ['coupon' => $coupon->id]) }}" class="btn-primary flex items-center space-x-1">
                                <!-- Ícone eye (Heroicons) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span>Visualizar</span>
                            </a>
                            @endcan

                            @can('edit-coupon')
                            <a href="{{ route('coupons.edit', ['coupon' => $coupon->id]) }}" class="btn-warning hidden md:flex items-center space-x-1">
                                <!-- Ícone pencil-square (Heroicons) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span>Editar</span>
                            </a>
                            @endcan

                            @can('destroy-coupon')
                            <form id="delete-form-{{ $coupon->id }}" action="{{ route('coupons.destroy', $coupon->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="button" onclick="confirmDelete('{{ $coupon->id }}')" class="btn-danger table-md-hidden">
                                    <!-- Ícone trash (Heroicons) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    <span>Apagar</span>
                                </button>
                            </form>
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

        <div class="mt-2 p-3">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
