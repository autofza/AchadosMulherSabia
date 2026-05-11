@extends('layouts.site')

@section('title', 'Termos de Uso - Achados Mulher Sábia')
@section('description', 'Leia os termos de uso do Achados Mulher Sábia. Entenda como funciona nossa curadoria, responsabilidades e política de preços.')

@section('content')
<main class="w-full">
    <article class="bg-white p-6 md:p-10 rounded-2xl shadow-sm border border-gray-100">

        <header class="mb-8 border-b border-gray-100 pb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                Termos de <span class="text-pink-600">Uso</span>
            </h1>
            <p class="text-gray-500 text-lg">Responsabilidade e transparência com você.</p>
        </header>

        <div class="space-y-8 text-gray-700 leading-relaxed text-base md:text-lg">
            
            <p class="text-justify text-lg">
                Ao utilizar o <strong>Achados Mulher Sábia</strong>, você concorda com os pontos estabelecidos abaixo. 
                Nosso compromisso é garantir uma experiência clara e segura para todas as nossas seguidoras.
            </p>

            <div class="grid gap-6 md:grid-cols-2 mb-6">
                
                <section class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-pink-200 transition-colors h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-pink-100 text-pink-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                        </div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">1. Natureza do Serviço</h2>
                    </div>
                    <p class="text-justify text-sm md:text-base text-gray-800">
                        Somos um serviço gratuito de curadoria e divulgação de ofertas. 
                        <strong>Não vendemos produtos diretamente</strong> nem somos responsáveis pela logística, entrega ou controle de estoque das lojas mencionadas.
                    </p>
                </section>

                <section class="bg-amber-50 p-5 rounded-xl border border-amber-100 h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-amber-100 text-amber-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">2. Preços e Promoções</h2>
                    </div>
                    <p class="text-justify text-sm md:text-base text-gray-800">
                        Os preços na internet mudam rápido. Garantimos que a oferta era real no momento da postagem, 
                        mas o <strong>valor final válido é sempre o que aparece no carrinho</strong> da loja oficial (
                        @foreach ($companys as $index => $company)
                            {{ $company->name }}
                            @if ($loop->last) @elseif ($loop->remaining == 1)
                                ,
                            @else
                                ,
                            @endif
                        @endforeach
                        e etc ). 
                    </p>
                </section>

                <section class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-pink-200 transition-colors h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">3. Garantia de Compra</h2>
                    </div>
                    <p class="text-justify text-sm md:text-base text-gray-800">
                        Qualquer questão relacionada a entrega, trocas, devoluções ou defeitos deve ser tratada 
                        <strong>diretamente com o suporte da loja</strong> onde a compra foi finalizada e o pagamento processado.
                    </p>
                </section>

                <section class="bg-blue-50 p-5 rounded-xl border border-blue-100 h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h2 class="text-lg md:text-xl font-bold text-blue-900">4. Transparência</h2>
                    </div>
                    <p class="text-sm md:text-base text-blue-800">
                        Declaramos que, como afiliados, podemos receber uma pequena comissão por vendas geradas através de nossos links. 
                        É isso que nos permite manter este serviço de curadoria <strong>100% gratuito para você</strong>.
                    </p>
                </section>

            </div>

            <div class="mt-10 p-6 bg-pink-50 rounded-xl text-center border border-pink-100">
                <p class="text-lg text-pink-900 font-medium mb-2">
                    "Comprar com sabedoria é comprar com segurança." 🔒
                </p>
                <p class="text-gray-600 mb-6">
                    Agora que você já sabe como trabalhamos, que tal ver o que separamos para hoje?
                </p>
                <a href="{{ route('site.ofertasDoDia') }}" class="px-5 py-3 w-full max-w-xs mx-auto text-white rounded-lg font-bold bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 hover:from-purple-600 hover:via-pink-600 hover:to-red-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg" >
                    <span>Ir para as Ofertas</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </article>
</main>
@endsection