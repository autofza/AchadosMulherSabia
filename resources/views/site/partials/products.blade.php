@forelse ($products as $product)
<div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 flex flex-col h-full">
    <!-- Imagem do produto -->
    <a href="{{ route('product.click', $product->id) }}" target="_blank" class="relative block bg-white overflow-hidden">
        <img src="{{ asset($product->image ? $product->image : 'uploads/imgSem.jpg') }}" alt="{{ $product->title }}" class="w-full h-full object-contain rounded-t-xl shadow-md hover:shadow-lg transition-shadow duration-300" style="aspect-ratio: 4 / 3;">

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

        <!-- ⚠️ Mensagem de aviso -->
        <p class="text-xs text-gray-500 mt-2 italic">
            ⚠️ Valores sujeitos a alteração no site oficial.
        </p>
    </div>

    <!-- Footer com botão -->
    <div class="bg-gray-50 p-1.5 border-t border-gray-100">
        <a href="{{ route('product.click', $product->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-1 px-3 rounded-lg font-semibold border-2 border-green-500 text-green-700 text-sm shadow-sm hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:via-emerald-500 hover:to-green-600 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7m0 0v7m0-7L10 14" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v11h11" />
            </svg>
            Ver na {{ $product->company->name}}
        </a>
    </div>
</div>
@empty
<div class="text-center text-gray-500 text-sm col-span-full">
    <div class="text-6xl mb-4">🛍️</div>
    <h4 class="text-2xl font-bold text-gray-600 mb-2">Nenhum produto encontrado</h4>
    <p class="text-gray-500">Tente ajustar sua busca nos filtros.</p>
</div>
@endforelse
