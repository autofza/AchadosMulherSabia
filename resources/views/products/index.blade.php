@extends('layouts.admin')
@isset($categories)
    {{-- ok --}}
@else
    @php($categories = collect())
@endisset

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">
            @if($company)
            Produtos da empresa: <strong>{{ $company->name }}</strong>
            @else
            Produtos
            @endif
        </h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            @if($company)
            <a href="{{ route('products.index') }}" class="breadcrumb-link">Todos os Produtos</a>
            <span>/</span>
            <span>{{ $company->name }}</span>
            @else
            <span>Produtos</span>
            @endif
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Listar</h3>
        <div class="content-box-btn">
            @can('create-product')
            <a href="{{ route('products.create') }}" class="btn-success align-icon-btn">
                <!-- Ícone plus-circle (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Cadastrar</span>
            </a>
            @endcan

            @can('generate-pdf-products')
            <a href="{{ url('products/generate-pdf/products') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn-warning align-icon-btn">
                <!-- Ícone document (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>PDF</span>
            </a>
            @endcan

            @can('generate-csv-products')
            <a href="{{ url('products/generate-csv/products') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn-success align-icon-btn">
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
    <form class="form-search">

        <input type="text" name="title" class="form-input" placeholder="Digite o título do produto" value="{{ old('title', $title) }}">
        <select name="category_id" id="category_id" class="form-input">
            <option value="">Selecione uma categoria</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (old('category_id', $category_id) == $category->id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>

        <input type="datetime-local" name="start_date_registration" class="form-input" value="{{ old('start_date_registration', $start_date_registration) }}">

        <input type="datetime-local" name="end_date_registration" class="form-input" value="{{ old('end_date_registration', $end_date_registration) }}">

        <div class="flex gap-1">
            <button type="submit" class="btn-primary flex items-center space-x-1">
                <!-- Ícone magnifying-glass (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <span>Pesquisar</span>
            </button>
            <a href="{{ route('products.index') }}" type="submit" class="btn-warning flex items-center space-x-1">
                <!-- Ícone trash (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                <span>Limpar</span>
            </a>
        </div>
    </form>
    <!-- Fim Formulário de Pesquisa -->

    <div class="table-container overflow-x-auto rounded-lg shadow-sm mt-6">
        <table class="table">
            <thead>
                <tr class="table-row-header">
                    <th class="table-header"></th>
                    <th class="table-header">Produtos</th>
                    <th class="table-header">Empresas</th>
                    <th class="table-header">Categorias</th>
                    <th class="table-header">Status</th>
                    <th class="table-header center">Ações</th>
                </tr>
            </thead>
            <tbody>
                {{-- Imprimir os registros --}}
                @forelse ($products as $product)
                <tr class="table-row-body">
                    <td class="table-body">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 overflow-hidden">
                            <img src="{{ asset($product->image ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="table-body whitespace-nowrap overflow-hidden text-ellipsis max-w-xs truncate">{{ $product->title }}</td>
                    <td class="table-body">
                        
                        {{-- Botão: Produtos da loja --}}
                        @if($product->company)
                        
                        <a href="{{ route('products.company', $product->company) }}" class="btn-success align-icon-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>
                            <span>{{ $product->company?->name }}</span>
                        </a>
                        @endif
                    </td>
                    <td class="table-body">{{ $product->category?->name  }}</td>
                    <td class="table-body">
                        <form id="toggle-{{ $product->id }}" action="{{ route('products.toggle-active', $product) }}" method="POST" class="hidden">
                            @csrf
                            @method('PUT')
                        </form>

                        <a href="#" onclick="confirmstatus('{{ $product->id }}')" class="flex items-center gap-1 text-sm font-medium transition hover:opacity-80 ml-2
                             {{ $product->active ? 'text-green-600 hover:text-green-800' : 'text-red-600 hover:text-red-800' }}">

                            @if($product->active)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Ativo</span>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Inativo</span>
                            @endif
                        </a>
                    </td>
                    <td class="table-actions">
                        <div class="table-actions-align">
                            @can('show-product')
                            <a href="{{ route('products.show', ['product' => $product]) }}" class="btn-primary align-icon-btn">
                                <!-- Ícone eye (Heroicons) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span>Visualizar</span>
                            </a>
                            @endcan

                            @can('edit-product')
                            <a href="{{ route('products.edit', ['product' => $product]) }}" class="btn-warning table-md-hidden">
                                <!-- Ícone pencil-square (Heroicons) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span>Editar</span>
                            </a>
                            @endcan

                            @can('destroy-product')
                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
                                @csrf
                                @method('delete')

                                <button type="button" onclick="confirmDelete('{{ $product->id }}')" class="btn-danger table-md-hidden">
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
            {{ $products->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
