@extends('layouts.site')

@section('title', 'Sobre Nós - Achados Mulher Sábia')
@section('description', 'Conheça a missão do Achados Mulher Sábia: ajudar mulheres a economizar de verdade com as melhores ofertas e promoções da internet.')

@section('content')
<main class="w-full">
    <article class="bg-white p-6 md:p-10 rounded-2xl shadow-sm border border-gray-100">

            <header class="mb-8 border-b border-gray-100 pb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                    Sobre a <span class="text-pink-600">{{ config('app.name') }}</span>
                </h1>
                <p class="text-gray-500 text-lg">Economia inteligente para mulheres reais.</p>
            </header>

            <div class="space-y-6 text-gray-700 leading-relaxed text-base md:text-lg">
                
                <p class="text-justify">
                    A <strong>Achados Mulher Sábia</strong> nasceu com um propósito simples e poderoso:
                    ajudar mulheres a <span class="bg-pink-50 text-pink-700 font-semibold px-1 rounded">economizar de verdade</span>, 
                    encontrando as melhores ofertas, promoções e achadinhos confiáveis da internet, tudo em um só lugar.
                </p>
                
                <p class="text-justify">
                    Aqui, cada oferta é selecionada com carinho, pensando no dia a dia real:
                    casa, beleza, moda, eletrodomésticos, utilidades e tudo aquilo que facilita
                    a rotina e cabe no bolso.
                </p>

                <section class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        🎯 Nossa missão
                    </h2>
                    <p class="mb-4">
                        Nossa missão é conectar você às melhores oportunidades, economizando
                        tempo e dinheiro, sem complicação.
                    </p>
                    
                    <ul class="grid sm:grid-cols-2 gap-3 mt-4">
                        <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg hover:bg-pink-50 transition-colors">
                            <span class="text-xl">🔍</span> Garimpamos ofertas
                        </li>
                        <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg hover:bg-pink-50 transition-colors">
                            <span class="text-xl">💰</span> Comparamos preços
                        </li>
                        <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg hover:bg-pink-50 transition-colors">
                            <span class="text-xl">🛍️</span> Lojas confiáveis
                        </li>
                        <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg hover:bg-pink-50 transition-colors">
                            <span class="text-xl">📲</span> Entrega rápida
                        </li>
                    </ul>
                </section>

                <section class="mt-8">
                    <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">
                        Como funcionam os achados
                    </h2>
                    <p class="text-justify">
                        As promoções são divulgadas diariamente no site e em nossos canais.
                        Trabalhamos com grandes lojas e marketplaces confiáveis.
                    </p>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 rounded-r-lg">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="text-blue-500 hidden sm:block">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        
                        <div class="text-gray-700 text-sm leading-relaxed">
                            <p class="text-justify">
                                <strong>Como funcionam nossos links?</strong> O Achados Mulher Sábia é um guia: nós indicamos, mas você compra direto na loja oficial (
                                @foreach ($companys as $index => $company)
                                        {{ $company->name }}
                                        @if ($loop->last)
                                            <!-- Nada depois do último -->
                                        @elseif ($loop->remaining == 1)
                                        ,
                                        @else
                                        ,
                                        @endif
                                    @endforeach
                                    e etc ). 
                                
                            </p>
                            <p class="mt-1 text-blue-900 font-medium">
                                💡 O preço <strong>nunca muda para você</strong> e sua compra ajuda a manter nosso site.
                            </p>
                        </div>
                    </div>
                </div>
                </section>

                <section class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        Por que confiar em nós?
                    </h2>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Curadoria 100% humana
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Selecionamos os melhores produtos com os melhores preços 
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Produtos com avaliações acima de 4.7 ⭐⭐⭐⭐
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Conteúdo pensado para mulheres reais
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Foco em economia inteligente
                        </li>
                    </ul>
                </section>

                <div class="mt-10 p-6 bg-pink-50 rounded-xl text-center">
                    <p class="text-lg text-pink-900 font-medium mb-2">
                        "Achadinhos que a gente ama, com o precinho que a gente precisa." ✨
                    </p>
                    <p class="text-gray-600 mb-6">
                        Seja muito bem-vinda 💕 Aproveite, compartilhe e economize.
                    </p>
                    <a href="{{ route('site.ofertasDoDia') }}" class="px-5 py-3 w-full max-w-xs mx-auto text-white rounded-lg font-bold bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 hover:from-purple-600 hover:via-pink-600 hover:to-red-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg" >
                        <span>Ver Ofertas de Hoje</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
    </article>
</main>
@endsection