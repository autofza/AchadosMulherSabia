<div id="sidebar" class="sidebar w-64 bg-gray-800 text-white h-screen transition-all duration-300 flex flex-col">
    <!-- Header -->
    <div id="sidebarHeader" class="sidebar-header p-4 cursor-pointer bg-gray-900 flex items-center justify-between">
        <span class="sidebar-title font-bold text-lg">Achados Mulher Sabia</span>

        <!-- Botão toggle -->
        <svg id="sidebarToggleIcon" xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6 text-white transition-transform duration-300"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </div>

    <!-- Links -->
    <ul class="mt-4 space-y-2 flex-1">
        <!-- Dashboard -->
        <li class="relative flex items-center p-2 hover:bg-gray-700 rounded-md cursor-pointer group">
            <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l2-2 7-7 7 7 2 2-9 9-9-9z"/>
            </svg>
            <span class="sidebar-text">Dashboard</span>

            <!-- Tooltip -->
            <span class="tooltip absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                Dashboard
            </span>
        </li>

        <!-- Configurações -->
        <li class="relative flex items-center p-2 hover:bg-gray-700 rounded-md cursor-pointer group">
            <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/>
            </svg>
            <span class="sidebar-text">Configurações</span>

            <!-- Tooltip -->
            <span class="tooltip absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                Configurações
            </span>
        </li>
    </ul>
</div>
