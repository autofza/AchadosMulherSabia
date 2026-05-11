@extends('layouts.admin')

@section('content')

<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Produtos</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="breadcrumb-link">Produtos</a>
            <span>/</span>
            <span>Detalhes</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Detalhes</h3>
        <div class="content-box-btn">
            @can('create-product')
            <a href="{{ route('products.create') }}" class="btn-success flex items-center space-x-1">
                <!-- Ícone plus-circle -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Cadastrar</span>
            </a>
            @endcan
            @can('index-product')
            <a href="{{ route('products.index') }}" class="btn-info align-icon-btn">
                <!-- Ícone queue-list (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan

            @can('edit-product')
            <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn-warning align-icon-btn">
                <!-- Ícone pencil-square (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                <span>Editar</span>
            </a>
            @endcan

            @can('destroy-product')
            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
                @csrf
                @method('delete')

                <button type="button" onclick="confirmDelete('{{ $product->id }}')" class="btn-danger flex items-center space-x-1">
                    <!-- Ícone trash (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
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
            <span class="title-detail-content">ID: </span>
            <span class="detail-content">{{ $product->id }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Imagem: </span>
            <span class="detail-content">
                <img src="{{ asset($product->image ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->title }}" class="w-50 h-50 object-cover rounded-full">
            </span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Título: </span>
            <span class="detail-content">{{ $product->title }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Slug: </span>
            <span class="detail-content">{{ $product->slug }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Categoria: </span>
            <span class="detail-content">{{ $product->category?->name }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Empresa: </span>
            <span class="detail-content">{{ $product->company->name ?? 'Loja não informada' }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Link do site: </span>
            <span class="detail-content"><a href="{{ $product->link }}" target="_blank">{{ $product->link }}</a></span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Descrição: </span>
            <span class="detail-content">{{ $product->description }}</span>
        </div>

        <div class="mb-1">
            <!-- Preço original riscado em vermelho -->
            <span class="title-detail-content">Preço original: </span>
            @if($product->original_price && $product->original_price > 0)
            <span class="detail-content text-red-600 line-through">
                R$ {{ number_format($product->original_price, 2, ',', '.') }}
            </span>
            @endif
        </div>

        <div class="mb-1">
            <!-- Preço original riscado em vermelho -->
            <span class="title-detail-content">Preço promocional: </span>
            <!-- Preço promocional -->
            @if($product->promo_price && $product->promo_price > 0)
            <span class="detail-content text-blue-400">
                R$ {{ number_format($product->promo_price, 2, ',', '.') }}
            </span>
            @endif
        </div>

        <div class="mb-1"> 
            <span class="title-detail-content">Cupom: </span>
            <span class="detail-content">{{ $product->coupon?->code }}</span>
        </div>


        <div class="mb-1 flex">
            <span class="title-detail-content">Publicado: </span>
            <span class="flex items-center gap-1 detail-content {{ $product->active ? 'text-green-600' : 'text-red-600' }} ml-2">
                @if($product->active)
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                @endif
                {{ $product->active ? 'Sim' : 'Não' }}
            </span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Data Inspirada: </span>
            <span class="detail-content text-amber-500">{{ \Carbon\Carbon::parse($product->inspired)->format('d/m/Y H:i:s') }}</span> 
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Criado: </span>
            <span class="detail-content">{{ \Carbon\Carbon::parse($product->created_at)->format('d/m/Y H:i:s') }}</span>
        </div>

        <div class="mb-1">
            <span class="title-detail-content">Editado: </span>
            <span class="detail-content">{{ $product->updated_at ? \Carbon\Carbon::parse($product->updated_at)->format('d/m/Y H:i:s') : '-' }}</span>
        </div>
        </>
    </div>
    @endsection