@extends('layouts.site')

@section('title', 'Política de Privacidade - Achados Mulher Sábia')
@section('description', 'Entenda como o Achados Mulher Sábia protege sua privacidade, utiliza cookies e garante sua segurança ao navegar pelas ofertas.')

@section('content')
<main class="w-full">
    <article class="bg-white p-6 md:p-10 rounded-2xl shadow-sm border border-gray-100">

        <header class="mb-8 border-b border-gray-100 pb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                Política de <span class="text-pink-600">Privacidade</span>
            </h1>
            <p class="text-gray-500 text-lg">Transparência e respeito com seus dados.</p>
        </header>

        <div class="space-y-8 text-gray-700 leading-relaxed text-base md:text-lg">
            
            <p class="text-justify text-lg">
                No <strong>Achados Mulher Sábia</strong>, respeitamos profundamente a sua privacidade. 
                Esta página explica de forma clara e direta como lidamos com as informações durante sua navegação.
            </p>

            <div class="grid gap-6 md:grid-cols-2 mb-6">
                
                <section class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-pink-200 transition-colors">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-pink-100 text-pink-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">1. Coleta de Dados</h2>
                    </div>
                    <p class="text-justify text-sm md:text-base text-gray-800">
                        <strong>Não coletamos dados pessoais sensíveis</strong> ou informações de pagamento. 
                        Nosso site funciona exclusivamente como uma vitrine de indicações e curadoria de ofertas.
                    </p>
                </section>

                <section class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-blue-900">2. Cookies e Afiliados</h2>
                    </div>
                    <p class="text-justify text-sm md:text-base text-gray-800">
                        Utilizamos cookies para rastreamento de links afiliados. Isso garante que, ao clicar em um "achadinho", 
                        a loja de destino saiba que a indicação veio daqui. 
                        <strong>Isso não gera custo adicional para você</strong> e não nos dá acesso aos seus dados bancários ou endereço.
                    </p>
                </section>

                <section class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-pink-200 transition-colors">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-pink-100 text-pink-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">3. Links Externos</h2>
                    </div>
                    <p class="text-justify text-sm md:text-base text-gray-800">
                        Ao clicar em "Ver Oferta", você será redirecionada para sites oficiais (
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
                        A partir desse momento, a política de privacidade válida é a da loja de destino onde a compra será realizada.
                    </p>
                </section>

                <section class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-pink-200 transition-colors">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">4. Segurança</h2>
                    </div>
                    <p class="text-justify text-sm md:text-base text-gray-800">
                        Nos comprometemos a manter um ambiente seguro. Realizamos uma curadoria rigorosa para garantir 
                        que nosso site esteja livre de links maliciosos, direcionando você apenas para varejistas renomados.
                    </p>
                </section>
            </div>

            <div class="mt-10 p-6 bg-pink-50 rounded-xl text-center border border-pink-100">
                <p class="text-lg text-pink-900 font-medium mb-2">
                    "Sua confiança é o nosso maior achado." 💖
                </p>
                <p class="text-gray-600 mb-6">
                    Ficou com alguma dúvida? Entre em contato conosco.
                </p>
                <a href="{{ route('site.ofertasDoDia') }}" class="px-5 py-3 w-full max-w-xs mx-auto text-white rounded-lg font-bold bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 hover:from-purple-600 hover:via-pink-600 hover:to-red-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg" >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Voltar para Ofertas</span>
                </a>
            </div>
        </div>
    </article>
</main>
@endsection