<div class="sidebar-container">
    <div class="sidebar-header" id="sidebarHeader">
        <div class="flex-shrink-0 flex items-center justify-center gap-0">
            <div class="p-[1px] rounded-full bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 shadow-md">
                <img src="{{ asset('images/logo.ico') }}" alt="Logo" class="h-8 w-8 rounded-full bg-white object-contain" />
            </div>
        </div>

        <span class="sidebar-title text-sm">Achados Mulher Sabia</span>

        <button id="toggleSidebar" class="p-1.5 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 ml-auto">
            <svg id="sidebarToggleIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 transition-transform duration-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        @can('dashboard')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'dashboard']) href="{{ route('dashboard.index') }}" x-tooltip="Dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="sidebar-text">Dashboard</span>
        </a>
        @endcan

        @can('index-user')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'users']) href="{{ route('users.index') }}" x-tooltip="Usuários">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>
            <span class="sidebar-text">Usuários</span>
        </a>
        @endcan

        @can('index-permission')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'permissions']) href="{{ route('permissions.index') }}" x-tooltip="Permissões">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
            </svg>
            <span class="sidebar-text">Permissões</span>
        </a>
        @endcan

        @can('index-role')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'roles']) href="{{ route('roles.index') }}" x-tooltip="Papéis">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
            </svg>
            <span class="sidebar-text">Papéis</span>
        </a>
        @endcan

        @can('index-category')
        <a @class([
            'sidebar-link',
            'active' => request()->routeIs('categories.index')
        ]) href="{{ route('categories.index') }}" x-tooltip="Categorias">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
            </svg>
            <span class="sidebar-text">Categorias</span>
        </a>
        @endcan

        {{-- Submenu: Palavras-chave --}}
        @if(auth()->user()->can('index-keyword-product') || auth()->user()->can('index-keyword-company'))
            @php
                $isProductActive = request()->routeIs('keyword_products.*');
                $isCompanyActive = request()->routeIs('keyword_companies.*');
                $isParentActive = $isProductActive || $isCompanyActive;
            @endphp

            <div x-data="{ isOpen: {{ $isParentActive ? 'true' : 'false' }} }">
                <a href="#" 
                   @click.prevent="isOpen = !isOpen"
                   @class(['sidebar-link justify-between cursor-pointer', 'active' => $isParentActive])
                   x-tooltip="'Palavras-chave'">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        <span class="sidebar-text">Palavras-chave</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="size-4 transition-transform duration-300"
                         :class="isOpen ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </a>

                <div x-show="isOpen" class="mt-1 space-y-1" style="display: none;">
                    @can('index-keyword-product')
                        <a @class(['sidebar-link pl-12', 'active' => $isProductActive]) href="{{ route('keyword_products.index') }}" x-tooltip="'Produtos'">
                            <span class="mr-2">•</span> Produtos
                        </a>
                    @endcan

                    @can('index-keyword-company')
                        <a @class(['sidebar-link pl-12', 'active' => $isCompanyActive]) href="{{ route('keyword_companies.index') }}" x-tooltip="'Empresas'">
                            <span class="mr-2">•</span> Empresas
                        </a>
                    @endcan
                </div>
            </div>
        @endif

        @can('index-company')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'companies']) href="{{ route('companies.index') }}" x-tooltip="Empresas">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 7.5-7.5 7.5 7.5M4.5 20.25h15a1.5 1.5 0 0 0 1.5-1.5V9.375a1.5 1.5 0 0 0-1.5-1.5h-5.25m-6 9v-6m6 6v-6" />
            </svg>
            <span class="sidebar-text">Empresas</span>
        </a>
        @endcan

        @can('index-product')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'products']) href="{{ route('products.index') }}" x-tooltip="Produtos">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.168 3.662a2.248 2.248 0 0 0-1.601-.662Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span class="sidebar-text">Produtos</span>
        </a>
        @endcan

        @can('index-coupon')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'coupons']) href="{{ route('coupons.index') }}" x-tooltip="Cupons">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15.75h6m-6-3H15m-6-3h6m6-1.5A2.25 2.25 0 0 1 18.75 6h-13.5A2.25 2.25 0 0 1 3 8.25v7.5A2.25 2.25 0 0 1 5.25 18h13.5A2.25 2.25 0 0 1 21 15.75v-7.5Z" />
            </svg>
            <span class="sidebar-text">Cupons</span>
        </a>
        @endcan

        @can('index-blogs')
        <a @class(['sidebar-link', 'active' => isset($menu) && $menu == 'blogs']) href="{{ route('blogs.index') }}" x-tooltip="Blogs">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="sidebar-text">Blogs</span>
        </a>
        @endcan

        {{-- Submenu: Relatórios --}}
        @if(auth()->user()->can('index-click-event') || auth()->user()->can('index-audit-event'))
            @php
                $isClickActive = request()->routeIs('click-events.*');
                $isAuditActive = request()->routeIs('audit-events.*');
                $isParentActive = $isClickActive || $isAuditActive;
            @endphp
        
            <div x-data="{ isOpen: {{ $isParentActive ? 'true' : 'false' }} }">
                <a href="#" 
                   @click.prevent="isOpen = !isOpen"
                   @class(['sidebar-link justify-between cursor-pointer', 'active' => $isParentActive])
                   x-tooltip="'Relatórios'">
                    <div class="flex items-center gap-3">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75h6a1.5 1.5 0 0 1 1.5 1.5V6h.75A1.5 1.5 0 0 1 18.75 7.5v11.25A1.5 1.5 0 0 1 17.25 20.25H6.75A1.5 1.5 0 0 1 5.25 18.75V7.5A1.5 1.5 0 0 1 6.75 6H7.5v-.75a1.5 1.5 0 0 1 1.5-1.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"  d="M9 10.5h6M9 13.5h6M9 16.5h4.5" />
                        </svg>
                        <span class="sidebar-text">Relatórios</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="size-4 transition-transform duration-300" :class="isOpen ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </a>
        
                <div x-show="isOpen" class="mt-1 space-y-1" style="display: none;">
                    @can('index-click-event')
                        <a @class(['sidebar-link pl-12', 'active' => $isClickActive]) href="{{ route('click-events.index') }}" x-tooltip="'Cliques'">
                            <span class="mr-2">•</span> Cliques
                        </a>
                    @endcan
        
                    @can('index-audit-event')
                        <a @class(['sidebar-link pl-12', 'active' => $isAuditActive]) href="{{ route('audit-events.index') }}" x-tooltip="'Auditoria'">
                            <span class="mr-2">•</span> Auditoria
                        </a>
                    @endcan
                </div>
            </div>
        @endif

        <a href="{{ route('logout') }}" class="sidebar-link" x-tooltip="Sair">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="sidebar-text">Sair</span>
        </a>
    </nav>
</div>
