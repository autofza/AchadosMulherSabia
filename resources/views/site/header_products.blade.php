<nav class="relative flex justify-center gap-3 flex-wrap sm:flex-nowrap py-1 hidden md:flex">
    {{-- Todas as ofertas --}}
   <a href="{{ route('site.home') }}" class="inline-flex px-3 py-1.5 rounded-full text-sm font-medium transition {{ !$selectedCompany ? 'bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-green-100 hover:text-green-700' }}">
        Todas as ofertas
    </a>

    @foreach($companys as $company)
    <div x-data="{ open: false }" class="relative group" @mouseenter="open = true" @mouseleave="open = false">

        {{-- BOTÃO PRINCIPAL --}}
        <a href="{{ route('site.byCompany', $company->slug) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium transition {{ $selectedCompany && $selectedCompany->id == $company->id ? 'bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-green-100 hover:text-green-700' }}">
            <span>{{ $company->name }}</span>
            @if($company->soon)
                <img src="{{ asset($company->soon) }}" class="w-6 h-6 rounded-full" />
            @endif
        </a>

        {{-- MEGA MENU --}}
        <div x-show="open" x-transition @click.outside="open = false" class="absolute top-full z-50 bg-white shadow-xl p-4 /* MOBILE */ left-0 w-screen max-w-none rounded-none /* DESKTOP */ md:left-1/2 md:w-[520px] md:-translate-x-1/2 md:rounded-xl">
            <h4 class="text-sm font-bold uppercase tracking-widest mb-3 flex items-center gap-2">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-fuchsia-600">
                    Categorias da {{ $company->name }}
                </span>
                @if($company->soon)
                    <img src="{{ asset($company->soon) }}" class="w-6 h-6 rounded-full" />
                @endif
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($company->categories as $category)
                <a href="{{ route('site.byCompanyCategory', [$company, $category]) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:text-fuchsia-600 focus:text-fuchsia-600 rounded-lg transition-colors duration-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-400 mr-2 opacity-60 group-hover:opacity-100 transition-opacity"></span>
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</nav>
