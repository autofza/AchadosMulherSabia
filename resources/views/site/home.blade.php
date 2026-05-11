@extends('layouts.site')

@section('header_products')
    @include('site.header_products')
@endsection

@section('content')
<!-- ================== CARROSSEL DE PRODUTOS EM DESTAQUE ================== -->

<!-- ========================== TÍTULO PRINCIPAL =========================== -->

<div class="py-4 max-w-7xl mx-auto mb-8 bg-gradient-to-b from-white to-fuchsia-50/30 rounded-lg overflow-hidden shadow-lg">
    <div class="mx-auto px-4"> {{-- ← Adicione este wrapper --}}
        @include('site.partials.breadcrumbs')
         
        <!-- Cabeçalho da Loja -->
        <div class="flex items-center p-2 border-b">
            <div class="flex items-center justify-between w-full">
                <div class="max-w-6xl mx-auto px-2 py-2 text-center">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 mb-4">
                        Os Melhores Achadinhos da
                        @if(isset($selectedCompany) && isset($selectedCategory))
                            {{-- $selectedCategory->name --}}  {{ $selectedCompany->name }}
                        @elseif(isset($selectedCompany))
                            Ofertas da {{ $selectedCompany->name }}
                        @else
                            @foreach ($companys as $index => $company)
                                {{ $company->name }}
                                @if ($loop->last)
                                    <!-- Nada depois do último -->
                                @elseif ($loop->remaining == 1)
                                    e
                                @else
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </h1>
                    <p class="text-gray-600 mb-2">
                        No Achados Mulher Sábia, selecionamos diariamente as melhores promoções e cupons para você economizar nas compras online.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-auto px-4 py-4">
        <!-- ================== LISTA DE PRODUTOS ================== -->
        <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @include('site.partials.products', ['products' => $products])
        </div>
        <!-- ================== LOAD MORE ================== -->

        @if ($hasMore ?? false)
            <div class="flex justify-center mt-6">
                <button 
                    id="loadMoreBtn"
                    data-page="{{ $page + 1 }}"
                    data-perpage="{{ $perPage }}"
                    data-company="{{ $selectedCompany->id ?? '' }}"
                    data-today="{{ request()->input('today', 0) }}"
                    data-search="{{ request()->input('search', '') }}"
                    data-category="{{ request()->input('category', '') }}"
                    class="px-5 py-3 w-full max-w-xs text-white rounded-lg font-bold bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 hover:from-purple-600 hover:via-pink-600 hover:to-red-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                    CARREGAR MAIS
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>
@endsection