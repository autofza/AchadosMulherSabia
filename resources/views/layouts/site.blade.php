<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="N1V54iSEyk_qFBTCXMQRwQ44aWv0Oy8n00-fsgKG7yw" />
    <title>{{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

    @vite(['resources/css/app_site.css', 'resources/js/app_site.js'])
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HYBHN3JBF1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-HYBHN3JBF1');
    </script>
    
     <!-- Microsoft Clarity -->
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "vx8qnn7atd");
    </script>
    
</head>
<body class="font-sans bg-gray-100">

    {{-- Header --}}
    @include('site.header')

    @yield('header_products')

    @yield('header_coupuns')

    {{-- Conteúdo --}}
    <main class="max-w-6xl mx-auto px-6 py-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('site.footer')

    @stack('scripts')
</body>
</html>
