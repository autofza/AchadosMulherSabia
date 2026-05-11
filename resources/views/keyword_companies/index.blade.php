@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Palavras-Chave (Empresas)</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <span>Palavras-Chave de Empresas</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Listar</h3>
        <div class="content-box-btn">
            {{-- Botão de Cadastro --}}
            @can('create-keyword-company')
            <a href="{{ route('keyword_companies.create') }}" class="btn-success align-icon-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Cadastrar</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />

    <form class="form-search" method="GET" action="{{ route('keyword_companies.index') }}">
        <input type="text" name="name" class="form-input" placeholder="Digite o nome" value="{{ $name ?? '' }}">
        <div class="flex gap-1">
            <button type="submit" class="btn-primary flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <span>Pesquisar</span>
            </button>
            
            <a href="{{ route('keyword_companies.index') }}" class="btn-warning flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                <span>Limpar</span>
            </a>
        </div>
    </form>
    <div class="table-container mt-6">
        <table class="table">
            <thead>
                <tr class="table-row-header">
                    <th class="table-header">ID</th>
                    <th class="table-header">Descrição</th>
                    <th class="table-header">Slug</th>
                    <th class="table-header hidden lg:table-cell">Empresa Vinculada</th>
                    <th class="table-header center">Ações</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop dos registros --}}
                @forelse ($keywords as $keyword)
                <tr class="table-row-body">
                    <td class="table-body w-20 whitespace-nowrap text-center">{{ $keyword->id }}</td>
                    <td class="table-body">{{ $keyword->name }}</td>
                    <td class="table-body">{{ $keyword->slug }}</td>
                    
                    {{-- Coluna da Empresa --}}
                    <td class="table-body hidden lg:table-cell">
                        @if($keyword->company)
                            <span class="badge bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                {{ $keyword->company->name }}
                            </span>
                        @else
                            <span class="text-gray-400">Sem Empresa</span>
                        @endif
                    </td>

                    <td class="table-actions">
                        <div class="table-actions-align">
                            {{-- Visualizar --}}
                            @can('show-keyword-company')
                            <a href="{{ route('keyword_companies.show', $keyword) }}" class="btn-primary align-icon-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span>Visualizar</span>
                            </a>
                            @endcan

                            {{-- Editar --}}
                            @can('edit-keyword-company')
                            <a href="{{ route('keyword_companies.edit', $keyword) }}" class="btn-warning table-md-hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span>Editar</span>
                            </a>
                            @endcan

                            {{-- Apagar --}}
                            @can('destroy-keyword-company')
                            
                            <form id="delete-form-{{ $keyword->id }}" action="{{ route('keyword_companies.destroy', $keyword) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $keyword->id }}')" class="btn-danger table-md-hidden">
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
                <tr>
                    <td colspan="5" class="alert-warning text-center p-4">
                        Nenhum registro encontrado!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-2 p-3">
            {{ $keywords->links() }}
        </div>
    </div>
</div>
@endsection