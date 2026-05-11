<header x-data="{ open: false }" class="w-full bg-gradient-to-r from-purple-500/80 via-pink-500/80 to-red-500/80 backdrop-blur-md shadow-lg sticky top-0 left-0 z-50">

    <!-- Barra superior (altura fixa = CLS resolvido) -->
    <div class="flex items-center justify-between h-10 px-2 md:px-4">

        <!-- Logo -->
        <a href="{{ route('site.home') }}" class="flex items-center gap-2">
            <div class="p-[1px] rounded-full bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 shadow-md">
                <img src="{{ asset('images/logo.ico') }}" alt="{{ config('app.name') }}" width="32" height="32" class="h-8 w-8 rounded-full bg-white object-contain" />
            </div>

            <span class="text-lg text-white font-semibold">
                {{ config('app.name') }}
            </span>
        </a>

        <!-- Menu Desktop -->
        <nav class="hidden md:flex space-x-4 text-sm font-semibold text-white">
            <a href="{{ route('site.home') }}">HOME</a>
            <a href="{{ route('site.ofertasDoDia') }}">OFERTAS DO DIA</a>
            <a href="{{ route('site.coupun_view') }}">CUPOM</a>
            <a href="{{ route('site.blog_view') }}">BLOG</a>
            <a href="{{ route('site.about') }}">SOBRE</a>
        </nav>

        <!-- Busca (desktop) -->
        <form action="{{ route('site.home') }}" method="get" class="relative hidden sm:block">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar ofertas..." class="pl-8 pr-3 py-0.5 w-52 text-sm rounded-full bg-white/20 border border-white/30 text-white placeholder-white/70 focus:ring-2 focus:ring-yellow-300 focus:outline-none" />
            <svg class="absolute left-2 top-1.5 h-4 w-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </form>

        <!-- Botão Mobile -->
        <button @click="open = !open" class="md:hidden text-white focus:outline-none" aria-label="Abrir menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- 🔥 MENU MOBILE SEO-FRIENDLY (LEVE) -->
    <nav  x-show="open" x-cloak x-transition class="md:hidden bg-gradient-to-r from-purple-600/95 via-pink-500/95 to-red-500/95 backdrop-blur-md px-3 py-2 space-y-2 text-sm text-white">

        <a href="{{ route('site.home') }}" class="block font-semibold">🏠 HOME</a>
        <a href="{{ route('site.ofertasDoDia') }}" class="block font-semibold">🔥 OFERTAS DO DIA</a>
        <a href="{{ route('site.coupun_view') }}" class="block font-semibold">🏷️ CUPONS</a>
        <a href="{{ route('site.blog_view') }}" class="block font-semibold">📰 BLOG</a>
        <a href="{{ route('site.about') }}" class="block font-semibold">ℹ️ SOBRE</a>
        
        <!-- VER TODAS AS LOJAS -->
        <div x-data="{ openAll: false }" class="border-t border-white/20 pt-2 mt-2">

            <!-- Botão principal -->
            <button @click="openAll = !openAll" class="flex items-center justify-between w-full text-white font-bold uppercase tracking-wider text-sm focus:outline-none">
                <span>🏪 VER TODAS LOJAS</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="{ 'rotate-180': openAll }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                </svg>
            </button>
        
            <!-- LISTA DE COMPANIES -->
            <div x-show="openAll" x-collapse class="mt-2 space-y-2">
                @foreach($companys as $company)
                    <div x-data="{ openCompany: false }" class="bg-white/10 rounded-lg">
        
                        <!-- COMPANY -->
                        <button  @click="openCompany = !openCompany" class="flex items-center justify-between w-full px-3 py-2 text-white font-semibold text-sm">
        
                            <div class="flex items-center gap-2">
                                @if($company->soon)
                                    <img src="{{ asset($company->soon) }}" alt="{{ $company->name }}" class="w-5 h-5 rounded-full bg-white" />
                                @endif
        
                                {{ $company->name }}
                            </div>
        
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform" :class="{ 'rotate-180': openCompany }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
        
                        <!-- CATEGORIAS DA COMPANY -->
                        <div x-show="openCompany" x-collapse class="ml-4 mb-2 space-y-1">
        
                            <!-- Ver todas da empresa -->
                            <a href="{{ route('site.byCompany', $company) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-white/90 text-sm hover:bg-white/20 transition">
                                <span class="text-xs">•</span>
                                Ver todas as ofertas da {{ $company->name }}
                            </a>
        
                            @foreach($company->categories as $category)
                                <a href="{{ route('site.byCompanyCategory', [$company, $category]) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-white/90 text-sm hover:bg-white/20 transition">
                                    <span class="text-xs">•</span>
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </nav>
</header>
