<nav aria-label="Breadcrumb" class="mb-2">
    <div class="flex flex-col items-end text-right">
        <ol class="flex flex-wrap items-center justify-end gap-2 text-sm font-semibold">

            <!-- Home -->
            <li>
                <a href="{{ route('site.home') }}"
                   class="text-gray-500 hover:text-pink-500 transition">
                    Home
                </a>
            </li>

            {{-- Ofertas do Dia --}}
            @isset($breadcrumbPage)
                <li class="text-gray-300">›</li>
                <li class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-fuchsia-600">
                    {{ $breadcrumbPage }}
                </li>
            @endisset

            {{-- Company --}}
            @isset($selectedCompany)
                <li class="text-gray-300">›</li>
                <li>
                    <a href="{{ route('site.byCompany', $selectedCompany->slug) }}"
                       class="text-gray-600 hover:text-fuchsia-600 transition">
                        {{ $selectedCompany->name }}
                    </a>
                </li>
            @endisset

            {{-- Category --}}
            @isset($selectedCategory)
                <li class="text-gray-300">›</li>
                <li class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-fuchsia-600">
                    {{ $selectedCategory->name }}
                </li>
            @endisset

        </ol>
    </div>
</nav>
