@extends('layouts.site')

@section('content')

<div class="max-w-6xl mx-auto mt-2 mb-2 p-2 bg-white rounded-lg shadow-sm text-center">
    <!-- Cabeçalho da Empresa -->
    <div class="flex items-center p-4 border-b">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center">
                <img src="{{ asset($product->company->soon ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->company->name }}" class="w-7 h-7 object-cover rounded-full bg-gray-100 border border-gray-200 mr-2">

                <div class="flex items-center space-x-1 text-sm text-gray-500">
                    <span> Oferta da empresa:</span>
                    <span class="font-semibold text-gray-800">{{ $product->company->name ?? 'Loja desconhecida' }}</span>
                </div>
            </div>

            <p class="text-sm text-gray-500 mb-2">
                Postado {{ $product->created_at->diffForHumans() }}
            </p>
        </div>
    </div>

    <!-- Imagem do Produto -->
    <div style="width: 320px; margin: 0 auto;"class="p-2">
        <img src="{{ asset($product->image ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->title }}" class="mx-auto rounded-xl shadow-md hover:shadow-lg transition-shadow max-h-36 object-contain">
        
        <!-- Desconto -->
        @php
            $desconto = 0;
            if ($product->original_price > 0 && $product->promo_price > 0) {
                $desconto = round((($product->original_price - $product->promo_price) / $product->original_price) * 100);
            }
        @endphp
    
        @if ($desconto > 0)
            <span class="absolute top-2 right-2 bg-pink-600 text-white text-[11px] font-bold px-2 py-0.5 rounded shadow z-10">
                -{{ $desconto }}% Off
            </span>
        @endif
    </div>

    <!-- Informações -->
    <div class="px-6 pb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800 mb-2 mt-2">
            {{ $product->title }}
        </h2>

        @if ($product->original_price)
        <p class="text-gray-400 line-through text-lg mt-1">
            R${{ number_format($product->original_price, 2, ',', '.') }}
        </p>
        @endif

        <p class="text-3xl font-extrabold text-purple-800 mt-1">
            R${{ number_format($product->promo_price, 2, ',', '.') }}
        </p>

        <!-- Botão de Pegar Oferta - CORRIGIDO PARA MOBILE -->
        <div class="mt-2">
            <a href="{{ route('product.click', $product->id) }}" target="_blank" class="inline-flex items-center justify-center gap-2 py-3 rounded-full font-bold 
                    border-2 border-green-500 text-green-700 text-sm 
                    hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:via-emerald-500 hover:to-green-600 
                    transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5 max-w-xs w-full mx-auto">
                Pegar Sua Oferta
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7m0 0v7m0-7L10 14" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v11h11" />
                </svg>
            </a>
        </div>

        <p class="text-xs text-gray-400 mt-4">
            * Preço e disponibilidade sujeito a alteração a qualquer momento. *
        </p>
    </div>
</div>

<!-- Carrossel de outras promoções da mesma categoria -->
<div class="max-w-6xl mx-auto mt-4">
    <h3 class="text-xl font-bold text-gray-800 mb-2">Itens que combinam com você</h3>
    <!-- Carrossel Responsivo -->
    <div x-data="carousel()" x-init="init(); startAutoSlide()" class="relative">
        <!-- Botão Esquerda -->
        <button @click="scroll(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 p-3 rounded-full z-10 bg-gradient-to-r from-purple-500/80 via-pink-500/80 to-red-500/80 text-white text-2xl shadow-lg transition transform duration-300 hover:scale-110 hover:shadow-2xl">
            ‹
        </button>

        <!-- Container do Carrossel -->
        <div x-ref="carousel" @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()" class="flex space-x-2 py-4 pb-6 overflow-x-auto scroll-smooth snap-x snap-mandatory" style="scroll-snap-type: x mandatory; scrollbar-width: none; -ms-overflow-style: none;">
            @foreach ($relatedProducts as $product)
            <!-- <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5 flex flex-col shrink-0 snap-start" style="flex: 1; min-width: 173px; max-width: 220px;">  -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5 flex flex-col shrink-0 snap-start w-48 md:w-64 h-64 overflow-hidden">
        <!-- Imagem -->
        <a href="{{ route('product.show', $product->id) }}" class="relative group block w-full h-48">
            <img src="{{ asset($product->image ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->title }}" class="w-full h-full object-cover">

                    <!-- Desconto -->
                    @php
                    $desconto = 0;
                    if ($product->original_price > 0 && $product->promo_price > 0) {
                    $desconto = round(
                    (($product->original_price - $product->promo_price) / $product->original_price) * 100
                    );
                    }
                    @endphp
                    @if ($desconto > 0)
                    <span class="absolute top-2 left-2 bg-pink-600 text-white text-xs font-bold px-2 py-0.5 rounded shadow">
                        -{{ $desconto }}% Off
                    </span>
                    @endif
                </a>

                <!-- Conteúdo -->
                <div class="p-2 flex-1">
                    @if ($product->company && $product->company->soon)
                    <div class="flex items-center justify-between mb-1">
                        <img src="{{ asset($product->company->soon) }}" alt="{{ $product->company->name }}" class="w-5 h-5 rounded-full object-cover mr-1">
                        <p class="text-gray-400 text-xs">
                            {{ $product->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    @endif

                    @if ($product->description)
                    <p class="text-gray-600 text-xs mb-1 line-clamp-2">
                        {{ Str::limit($product->description, 80) }}
                    </p>
                    @endif

                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-gray-500 line-through">
                            R$ {{ number_format($product->original_price, 2, ',', '.') }}
                        </span>
                        <span class="text-sm font-bold text-purple-800">
                            R$ {{ number_format($product->promo_price, 2, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Botão -->
                <div class="bg-gray-50 p-2">
                    <a href="{{ route('product.show', $product->id) }}" class="w-full flex items-center justify-center gap-2 py-1 px-3 rounded-lg font-semibold 
                                 border-2 border-green-500 text-green-700 text-sm shadow-sm 
                                 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:via-emerald-500 hover:to-green-600 
                                 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7m0 0v7m0-7L10 14" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v11h11" />
                        </svg>
                        Ver a Oferta
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Botão Direita -->
        <button @click="scroll(1)" class="absolute right-0 top-1/2 -translate-y-1/2 p-3 rounded-full z-10 bg-gradient-to-r from-purple-500/80 via-pink-500/80 to-red-500/80 text-white text-2xl shadow-lg transition transform duration-300 hover:scale-110 hover:shadow-2xl">
            ›
        </button>

        <!-- Indicadores -->
        <div class="flex justify-center mt-3 space-x-2">
            <template x-for="(dot, index) in total" :key="index">
                <button @click="goTo(index)" :class="currentIndex === index ? 'bg-pink-600 scale-125' : 'bg-gray-300'" class="w-3 h-3 rounded-full transition transform duration-300 hover:scale-125"></button>
            </template>
        </div>
    </div>
</div>

    <!-- Link do WhatsApp -->
    <div class="max-w-6xl mx-auto mt-4 mb-4 p-6 bg-white rounded-lg shadow-sm text-center">
        <h4 class="text-lg font-semibold text-gray-800 mb-1">
            Quer economizar de verdade?
        </h4>
        <p class="text-gray-600 mb-4">
            Receba no WhatsApp as melhores ofertas e economize mais todo dia!
        </p>
        <a href="{{ route('watsapp.click') }}" target="_blank" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-2 rounded-full font-bold text-white bg-green-500 hover:bg-green-600 transition-colors">
            <!-- Ícone do WhatsApp -->
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.52 3.48A11.77 11.77 0 0012 0C5.37 0 0 5.37 0 12c0 2.1.54 4.16 1.57 5.97L0 24l6.2-1.6A11.93 11.93 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.84 0-3.62-.49-5.18-1.42l-.37-.22-3.68.95.98-3.58-.24-.37A9.94 9.94 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.95 9.95 0 0122 12c0 5.52-4.48 10-10 10zm5.23-7.77c-.29-.15-1.72-.85-1.99-.94-.27-.1-.47-.15-.67.15-.2.29-.77.94-.95 1.14-.18.2-.35.22-.64.07-.29-.15-1.22-.45-2.32-1.44-.86-.76-1.44-1.7-1.61-1.99-.17-.29-.02-.45.13-.6.13-.13.29-.35.44-.52.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.79.37-.27.29-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.1 4.49.71.31 1.26.49 1.69.63.71.23 1.35.2 1.86.12.57-.08 1.72-.7 1.96-1.37.24-.67.24-1.25.17-1.37-.06-.12-.26-.2-.55-.35z" />
            </svg>
            Clique aqui para entrar
        </a>
    </div>

    <!-- Veja outras ofertas -->
    <div class="max-w-6xl mx-auto mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Confere outras ofertas !</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">

        @foreach ($products as $product)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 flex flex-col h-full">
            <!-- Imagem do produto -->
            <a href="{{ route('product.show', $product->id) }}" class="relative block">
                <img src="{{ asset($product->image ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->title }}" class="w-full h-auto" />


                @php
                $desconto = 0;
                if ($product->original_price > 0 && $product->promo_price > 0) {
                $desconto = round((($product->original_price - $product->promo_price) / $product->original_price) * 100);
                }
                @endphp

                @if ($desconto > 0)
                <span class="absolute top-2 right-2 bg-pink-600 text-white text-[11px] font-bold px-2 py-0.5 rounded shadow-sm z-10">
                    -{{ $desconto }}% OFF
                </span>
                @endif
            </a>

            <!-- Conteúdo -->
            <div class="p-3 flex-1 flex flex-col">
                @if ($product->company && $product->company->soon)
                <div class="flex items-center justify-between mb-2">
                    <img src="{{ asset($product->company->soon ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->company->name ?? 'Empresa' }}" class="w-8 h-8 object-cover rounded-full bg-gray-100 border border-gray-200">
                    <p class="text-gray-400 text-xs">
                        {{ $product->updated_at->diffForHumans() }}
                    </p>
                </div>
                @endif

                @if ($product->title)
                <p class="text-gray-700 text-sm font-medium mb-2 line-clamp-2">
                    {{ Str::limit($product->title, 80) }}
                </p>
                @endif

                <div class="mt-auto">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 line-through">
                            R$ {{ number_format($product->original_price, 2, ',', '.') }}
                        </span>
                        <span class="text-sm font-bold text-purple-700">
                            R$ {{ number_format($product->promo_price, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer com botão -->
            <div class="bg-gray-50 p-2 border-t border-gray-100">
                <a href="{{ route('product.show', $product->id) }}" class="w-full flex items-center justify-center gap-2 py-1 px-3 rounded-lg font-semibold 
                 border-2 border-green-500 text-green-700 text-sm shadow-sm 
                 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:via-emerald-500 hover:to-green-600 
                 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7m0 0v7m0-7L10 14" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v11h11" />
                    </svg>
                    Ver a Oferta
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endsection

    @push('scripts')
    <!-- Scripts personalizados (ex: máscara de preço) -->
    @endpush
