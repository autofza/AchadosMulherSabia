<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Grupo VIP | Achados Mulher Sábia</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Entre no Grupo VIP Achados Mulher Sábia e receba promoções reais, achados exclusivos e ofertas por tempo limitado no WhatsApp.">

    {{-- Meta Pixel --}}
    <script>
        !function(f,b,e,v,n,t,s){
            if(f.fbq)return;
            n=f.fbq=function(){
                n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)
            };
            if(!f._fbq)f._fbq=n;
            n.push=n;
            n.loaded=!0;
            n.version='2.0';
            n.queue=[];
            t=b.createElement(e);t.async=!0;
            t.src=v;
            s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)
        }(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '{{ config("services.meta.pixel_id") }}');
        fbq('track', 'ViewContent');
    </script>

    @vite(['resources/css/app_site.css'])

    {{-- Auto redirecionamento + evento de conversão --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const redirectUrl = "{{ route('grupo.vip.redirect') }}";
            const delay = 5000; // 5 segundos

            setTimeout(function () {

                if (typeof fbq !== 'undefined') {
                    fbq('track', 'Lead');
                    fbq('track', 'CompleteRegistration');
                }

                window.location.href = redirectUrl;

            }, delay);

        });
    </script>
</head>

<body class="h-full bg-gradient-to-br from-pink-50 via-white to-fuchsia-100 overflow-x-hidden">
    <div class="flex min-h-screen items-center justify-center px-4">
        <main class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-8 text-center animate-fade-in">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.ico') }}" 
                     alt="Achados Mulher Sábia" 
                     class="w-20 h-20 rounded-full">
            </div>

            <!-- Headline -->
            <h1 class="text-3xl font-extrabold text-gray-800 mb-3">
                Não perca nenhuma promoção!
            </h1>

            <!-- Subheadline -->
            <p class="text-gray-600 mb-6 leading-relaxed">
                💥 Receba <strong>achados verificados</strong>, descontos reais e
                ofertas por tempo limitado diretamente no
                <strong>Grupo VIP do WhatsApp</strong>.
            </p>

            <!-- Prova social -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-3 mb-6 text-sm text-green-700">
                🔥 Mais de <strong>milhares de pessoas</strong> já economizam todos os dias
            </div>

            <!-- CTA -->
            <a href="{{ route('grupo.vip.redirect') }}"
               onclick="fbq('track', 'Lead'); fbq('track', 'CompleteRegistration');"
               class="block bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg text-lg">
                👉 Entrar no Grupo VIP agora
            </a>

            <!-- Saída suave -->
            <a href="{{ url('/') }}" class="block mt-5 text-sm text-gray-400 hover:text-gray-600 hover:underline">
                Agora não, quero continuar no site
            </a>

            <!-- Rodapé -->
            <p class="mt-6 text-xs text-gray-400">
                🔒 Sem spam • Você pode sair quando quiser
            </p>

        </main>
    </div>
</body>
</html>
