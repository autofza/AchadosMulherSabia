@extends('layouts.site')

@section('header_coupuns')
@include('site.header_coupuns')
@endsection

@section('content')
<div class="max-w-6xl mx-auto mb-4 px-4 py-4 bg-white rounded-lg shadow-sm text-center">
    @if($coupons->isNotEmpty())
    {{-- Cabeçalho (fixo, aparece só uma vez) --}}
    <div class="max-w-6xl mx-auto px-4 py-2 text-center mb-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Cupons Disponíveis</h1>
        <p class="text-sm text-gray-600">Aproveite as melhores ofertas das nossas empresas parceiras!</p>
    </div>
    @endif
    {{-- Grid de cupons --}}
    <div class="max-w-6xl mx-auto px-4 py-2 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($coupons as $c)
        <div class="relative w-full p-4 rounded-2xl shadow-xl text-white font-sans overflow-hidden 
                          bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 block hover:scale-105 transition">

            {{-- Header do card --}}
            <div class="mb-2 flex justify-center">
                <h3 class="text-lg font-extrabold drop-shadow-lg">
                    {{ optional($c->company)->name ?? 'Empresa não definida' }}
                </h3>
            </div>

            {{-- Conteúdo do card --}}
            <div class="flex gap-3">
                <div class="w-12 h-12 flex items-center justify-center bg-white/25 rounded-full overflow-hidden">
                    <img src="{{ asset(optional($c->company)->soon ?? 'uploads/imgSem.jpg') }}" alt="{{ optional($c->company)->name }}" class="w-10 h-10 object-cover rounded-full">
                </div>
                <div class="flex-2 justify-center">
                    <h3 class="text-1xl font-extrabold drop-shadow-lg">⏳ Aproveite AGORA!</h3>
                    <p class="text-sm font-medium">CUPOM: {{ $c->code }}</p>
                </div>
            </div>

            {{-- Footer do card --}}
            <a href="{{ route('coupon.click', $c->id) }}" target="_blank">
                <div class="mt-2 border-t border-white/20 pt-1 flex items-center justify-between text-xs text-white/80">
                    <span class="w-70">Não perca tempo, é limitado! ⏰</span>
                    <button class="w-30 bg-white text-purple-600 font-bold rounded-md px-2 py-1 hover:bg-purple-50 active:scale-95 transition text-xs flex items-center justify-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7m0 0v7m0-7L10 14" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v11h11" />
                        </svg>
                        Pegar
                    </button>
                </div>
            </a>
            {{-- Círculos decorativos --}}
            @foreach (['tl' => 'left-0 top-4 -translate-x-1/2', 'bl' => 'left-0 bottom-4 -translate-x-1/2', 'tr' => 'right-0 top-4 translate-x-1/2', 'br' => 'right-0 bottom-4 translate-x-1/2'] as $cls)
            <span class="absolute w-6 h-6 bg-white/20 rounded-full {{ $cls }}"></span>
            @endforeach
        </div>
        @empty
        <div class="col-span-full flex flex-col items-center justify-center text-center text-gray-500 gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m-7 4h16a2 2 0 002-2v-4a2 2 0 00-2-2h-1a1 1 0 110-2h1a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h1a1 1 0 110 2H5a2 2 0 00-2 2v4a2 2 0 002 2z" />
            </svg>
            <h4 class="text-2xl font-bold text-gray-600">Nenhum cupom encontrado</h4>
            <p class="text-gray-500 text-sm">Não se esqueça de ver nossas ofertas!</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Link do WhatsApp --}}
<div class="max-w-6xl mx-auto mt-1 mb-8 p-4 bg-white rounded-lg shadow-sm text-center">
    <h4 class="text-lg font-semibold text-gray-800 mb-1">Quer economizar de verdade?</h4>
    <p class="text-gray-600 mb-4">Receba no WhatsApp as melhores ofertas e economize mais todo dia!</p>
    <a href="{{ route('watsapp.click') }}" target="_blank" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-2 rounded-full font-bold text-white bg-green-500 hover:bg-green-600 transition-colors">
        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.52 3.48A11.77 11.77 0 0012 0C5.37 0 0 5.37 0 12c0 2.1.54 4.16 1.57 5.97L0 24l6.2-1.6A11.93 11.93 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.84 0-3.62-.49-5.18-1.42l-.37-.22-3.68.95.98-3.58-.24-.37A9.94 9.94 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.95 9.95 0 0122 12c0 5.52-4.48 10-10 10zm5.23-7.77c-.29-.15-1.72-.85-1.99-.94-.27-.1-.47-.15-.67.15-.2.29-.77.94-.95 1.14-.18.2-.35.22-.64.07-.29-.15-1.22-.45-2.32-1.44-.86-.76-1.44-1.7-1.61-1.99-.17-.29-.02-.45.13-.6.13-.13.29-.35.44-.52.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.79.37-.27.29-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.1 4.49.71.31 1.26.49 1.69.63.71.23 1.35.2 1.86.12.57-.08 1.72-.7 1.96-1.37.24-.67.24-1.25.17-1.37-.06-.12-.26-.2-.55-.35z" />
        </svg>
        Clique aqui para entrar
    </a>
</div>

{{-- Paginação --}}
<div class="mt-6">{{ $coupons->links() }}</div>
@endsection
