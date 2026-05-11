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
            <span>Editar</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Editar</h3>
        <div class="content-box-btn">
            @can('index-product')
            <a href="{{ route('products.index') }}" class="btn-info align-icon-btn">
                <!-- Ícone queue-list (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="form-label">Título</label>
                <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $product->title) }}" required>
            </div>

            <!-- Link -->
            <div class="mb-4">
                <label for="link" class="form-label">Link</label>
                <input type="text" name="link" id="link" class="form-input" value="{{ old('link', $product->link) }}" required>
            </div>
        </div>
        <!-- Descrição -->
        <div class="mb-4">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description" rows="2" class="form-input" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <!-- Container responsivo: 1 coluna em mobile, 2 colunas a partir de md -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Categoria -->
            <div>
                <label for="category_id" class="form-label block mb-1">Categoria</label>
                <select name="category_id" id="category_id" class="form-input w-full" required>
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Loja -->
            <div>
                <label for="company_id" class="form-label block mb-1">Loja</label>
                <select name="company_id" id="company_id" class="form-input w-full" required>
                    <option value="">Selecione uma loja</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Container responsivo: 1 coluna em mobile, 2 colunas a partir de md (768px) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Preço Original -->
            <div>
                <label for="original_price" class="form-label block mb-1">Preço Original</label>
                <input type="text" name="original_price" id="original_price" class="form-input w-full" value="{{ old('original_price', number_format($product->original_price, 2, ',', '.')) }}" data-type="currency" required>
            </div>

            <!-- Preço Promocional -->
            <div>
                <label for="promo_price" class="form-label block mb-1">Preço Promocional</label>
                <input type="text" name="promo_price" id="promo_price" class="form-input w-full" value="{{ old('promo_price', number_format($product->promo_price, 2, ',', '.')) }}" data-type="currency" required>
            </div>
        </div>

        <!-- Container responsivo: 1 coluna em mobile, 2 em md, 3 em lg -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Cupom -->
            <div>
                <label for="coupon_id" class="form-label block mb-1">Cupom</label>
                <select name="coupon_id" id="coupon_id" class="form-input w-full ">
                    <option value="">Selecione um cupom</option>
                    @foreach($coupons as $coupon)
                    <option value="{{ $coupon->id }}" {{ old('coupon_id', $product->coupon_id) == $coupon->id ? 'selected' : '' }}>
                        {{ $coupon->code }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Data Inspirada -->
            <div>
                <label for="inspired" class="form-label block mb-1">Data Inspirada</label>
                <input type="datetime-local" name="inspired" id="inspired" class="form-input w-full" value="{{ $product->inspired ? \Carbon\Carbon::parse($product->inspired)->format('Y-m-d\TH:i') : '' }}">
            </div>

            <!-- Publicado -->
            <div>
                <label for="active" class="form-label block mb-1">Publicado?</label>
                <select name="active" id="active" class="form-input w-full">
                    <option value="0" {{ old('active', $product->active) == 0 ? 'selected' : '' }}>Não</option>
                    <option value="1" {{ old('active', $product->active) == 1 ? 'selected' : '' }}>Sim</option>
                </select>
            </div>
        </div>

        <!-- Container: 1 coluna em mobile, 2 colunas em telas médias+ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Upload de nova imagem -->
            <div>
                <label for="image" class="form-label block mb-1">Trocar Imagem</label>
                <input type="file" name="image" id="image" class="form-input w-full" accept="image/*">
                <small class="text-gray-500 dark:text-gray-400 text-sm block mt-1">
                    💡 Cole (Ctrl+V) ou arraste uma imagem aqui. Deixe em branco para manter a atual.
                </small>
            </div>

            <!-- Preview da imagem atual ou nova -->
            <div>
                <label class="form-label block mb-2">Pré-visualização</label>
                <div id="image-preview-container" class="w-48 h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50">
                    @if($product->image)
                    <img id="image-preview" src="{{ asset($product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                    <span id="image-placeholder" class="text-gray-400 text-sm hidden">Cole ou selecione uma imagem</span>
                    @else
                    <img id="image-preview" src="{{ asset('uploads/imgSem.jpg') }}" alt="Imagem padrão" class="w-full h-full object-cover hidden">
                    <span id="image-placeholder" class="text-gray-400 text-sm">Imagem não definida</span>
                    @endif
                </div>
            </div>
        </div>
        <!-- Botão -->
        <button type="submit" class="btn-success align-icon-btn">
            <span>Salvar Alterações</span>
        </button>
    </form>
</div>
@endsection

@push('scripts')
@endpush
