@extends('layouts.site')

@section('content')

<div class="max-w-lg mx-auto mt-6 mb-10 p-4 bg-white rounded-xl shadow text-center">

    {{-- Empresa --}}
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <img src="{{ asset($product->company->soon ?? 'uploads/imgSem.jpg') }}"
                 class="w-8 h-8 rounded-full object-cover border">
            <span class="text-sm text-gray-600">
                Oferta da <strong>{{ $product->company->name ?? 'Loja parceira' }}</strong>
            </span>
        </div>

        <span class="text-xs text-gray-400">
            {{ $product->created_at->diffForHumans() }}
        </span>
    </div>

    {{-- Imagem --}}
    <div class="flex justify-center my-4">
        <img src="{{ asset($product->image) }}"
             alt="{{ $product->title }}"
             class="max-h-48 rounded-lg shadow object-contain">
    </div>

    {{-- Título --}}
    <h1 class="text-lg font-bold text-gray-800 mb-2">
        {{ $product->title }}
    </h1>

    {{-- Preços --}}
    @if ($product->original_price)
        <p class="text-gray-400 line-through text-sm">
            R$ {{ number_format($product->original_price, 2, ',', '.') }}
        </p>
    @endif

    <p class="text-3xl font-extrabold text-purple-800 mt-1">
        R$ {{ number_format($product->promo_price, 2, ',', '.') }}
    </p>

    {{-- Aviso --}}
    <div class="mt-4 text-sm text-gray-600">
        Você será redirecionado(a) para o site oficial da loja em
        <span id="countdown" class="font-bold text-purple-700">3</span> segundos…
    </div>

    {{-- Botão manual --}}
    <div class="mt-4">
        <a href="{{ route('product.click', $product->id) }}"
           class="inline-flex items-center justify-center w-full py-3 rounded-full font-bold
                  text-white bg-green-500 hover:bg-green-600 transition">
            Continuar para a loja
        </a>
    </div>

    <p class="text-xs text-gray-400 mt-4">
        * Preço e disponibilidade podem ser alterados a qualquer momento.
    </p>
</div>

@endsection

@push('scripts')
<script>
    let seconds = 3;
    const countdown = document.getElementById('countdown');

    const timer = setInterval(() => {
        seconds--;
        countdown.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = "{{ route('product.click', $product->id) }}";
        }
    }, 1000);
</script>
@endpush
