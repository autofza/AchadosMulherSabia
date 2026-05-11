<!-- Footer Ultra-Premium Neon + Carousel -->
<footer class="relative bg-fuchsia-100 py-4 overflow-hidden">
    <!-- Gradiente leve no background -->
    <div class="absolute inset-0 bg-gradient-to-t from-gray-200 via-gray-100 to-gray-50 opacity-30 pointer-events-none">
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        
            <!-- Sobre -->
            <div class="md:col-span-3 text-center">
                <div class="flex items-center justify-center space-x-3 mb-3">
                    <a href="{{ route('site.home') }}" class="block transform hover:scale-105 transition duration-300">
                        <img src="{{ asset('images/logo.ico') }}" alt="Logo" class="h-12 w-12 rounded-full block">
                    </a>
                    <h3 class="text-base font-bold">Achados Mulher Sabia</h3>
                </div>

                <p class="text-gray-700 mb-3 text-center">Siga-nos nas redes sociais e não perca nenhuma oferta!</p>

                <!-- Grupo de Redes Sociais - CENTRALIZADO EM MOBILE -->
                <div class="flex justify-center space-x-4 mt-4">
                
                    <svg width="0" height="0" class="absolute block">
                        <defs>
                            <linearGradient id="global-social-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#d946ef" />
                                <stop offset="50%" stop-color="#ec4899" />
                                <stop offset="100%" stop-color="#f87171" />
                            </linearGradient>
                        </defs>
                    </svg>
                
                    <a href="{{ route('watsapp.click') }}" target="_blank" class="hover:scale-110 transform transition duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 32 32">
                            <path fill="url(#global-social-gradient)" d="M16 .4C7.2.4.1 7.5.1 16.3c0 2.9.8 5.7 2.3 8.1L.1 31.6l7.4-2.2c2.3 1.3 4.9 2 7.6 2 8.8 0 15.9-7.1 15.9-15.9S24.8.4 16 .4zm0 28.9c-2.5 0-4.9-.7-7-2.1l-.5-.3-4.4 1.3 1.4-4.3-.3-.5c-1.4-2.1-2.2-4.6-2.2-7.1 0-7.3 5.9-13.2 13.2-13.2s13.2 5.9 13.2 13.2-5.9 13-13.2 13zm7.3-9.7c-.4-.2-2.1-1-2.4-1.1s-.6-.2-.9.2-1 1.1-1.2 1.4-.4.3-.8.1c-.4-.2-1.8-.7-3.4-2.2-1.2-1.1-2-2.4-2.2-2.8s0-.6.2-.8c.2-.2.4-.5.6-.7.2-.2.3-.4.4-.6.1-.2 0-.5 0-.7-.1-.2-.9-2.2-1.2-3-.3-.7-.6-.6-.9-.6h-.8c-.3 0-.7.1-1 .5s-1.3 1.2-1.3 3c0 1.8 1.3 3.5 1.5 3.8.2.3 2.6 4 6.3 5.6.9.4 1.6.6 2.1.8.9.3 1.7.2 2.3.1.7-.1 2.1-.9 2.4-1.8.3-.9.3-1.6.2-1.8-.2-.2-.5-.3-.9-.5z" />
                        </svg>
                    </a>
                
                    <a href="{{ route('instagram.click') }}" target="_blank" class="hover:scale-110 transform transition duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24">
                            <path fill="url(#global-social-gradient)" d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9A5.5 5.5 0 0 1 16.5 22h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm5.75-3a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5z" />
                        </svg>
                    </a>
                
                    <a href="{{ route('pinterest.click') }}" target="_blank" class="hover:scale-110 transform transition duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24">
                            <path fill="url(#global-social-gradient)" d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.561-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.498 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z" />
                        </svg>
                    </a>
                
                    <a href="{{ route('telegram.click') }}" target="_blank" class="hover:scale-110 transform transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-6 h-6">
                            <path fill="url(#global-social-gradient)" d="M25,2c12.703,0,23,10.297,23,23S37.703,48,25,48S2,37.703,2,25S12.297,2,25,2z M32.934,34.375 c0.423-1.298,2.405-14.234,2.65-16.783c0.074-0.772-0.17-1.285-0.648-1.514c-0.578-0.278-1.434-0.139-2.427,0.219 c-1.362,0.491-18.774,7.884-19.78,8.312c-0.954,0.405-1.856,0.847-1.856,1.487c0,0.45,0.267,0.703,1.003,0.966 c0.766,0.273,2.695,0.858,3.834,1.172c1.097,0.303,2.346,0.04,3.046-0.395c0.742-0.461,9.305-6.191,9.92-6.693 c0.614-0.502,1.104,0.141,0.602,0.644c-0.502,0.502-6.38,6.207-7.155,6.997c-0.941,0.959-0.273,1.953,0.358,2.351 c0.721,0.454,5.906,3.932,6.687,4.49c0.781,0.558,1.573,0.811,2.298,0.811C32.191,36.439,32.573,35.484,32.934,34.375z" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Empresas -->
            <div class="md:col-span-2">
                <h4 class="text-lg font-bold mb-3">Ver Ofertas da</h4>
                <ul class="space-y-1">
                    @foreach ($companys as $company)
                    <li>
                        <a href="{{ route('site.byCompany', $company->slug) }}" class="inline-block">
                        <span class="text-gray-700" onmouseenter="this.style.backgroundImage='linear-gradient(to right, #ec4899, #c026d3)'; this.style.webkitBackgroundClip='text'; this.style.backgroundClip='text'; this.style.color='transparent';" onmouseleave="this.style.backgroundImage=''; this.style.color='#374151';">
                            {{ $company->name }}
                        </span>
                    </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Ofertas do Dia -->
            <div class="md:col-span-4 text-center">
                <h4 class="text-lg font-bold mb-2">Ofertas do Dia</h4> 
                <div x-data="carousel" x-init="init(); startAutoSlide()"  @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()" class="relative overflow-hidden bg-fuchsia-50/20 rounded-xl p-1.5" role="region" aria-label="Carrossel de ofertas do dia">
                    <div x-ref="carousel" class="flex gap-2 overflow-x-auto scroll-smooth pb-1.5 hide-scrollbar snap-x snap-mandatory" tabindex="0">
                        @forelse ($footerProducts as $product)
                            <div class="snap-start flex-shrink-0 w-36 bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 flex flex-col h-[140px]">
                                <a href="{{ route('product.click', $product->id) }}" target="_blank" rel="noopener noreferrer" class="block h-20">
                                    <img src="{{ asset($product->image ?? 'uploads/imgSem.jpg') }}" alt="{{ $product->title ?? 'Produto sem título' }}" class="w-full h-full object-contain bg-white" loading="lazy">
                                </a>
                                <div class="p-1.5 flex flex-col justify-between h-[70px]">
                                    <a href="{{ route('product.click', $product->id) }}"  target="_blank" rel="noopener noreferrer" class="text-[10px] font-medium text-gray-700 line-clamp-2 hover:text-fuchsia-600 transition">
                                        {{ Str::limit($product->title, 30) }}
                                    </a>
                                    <div>
                                        <span class="text-[9px] text-gray-500 line-through">
                                            R$ {{ number_format($product->original_price, 2, ',', '.') }}
                                        </span>
                                        <div class="font-bold text-green-600 text-[10px] mt-0.5">
                                            R$ {{ number_format($product->promo_price, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-500 text-sm py-3 px-4">
                                Nenhuma oferta disponível hoje.
                            </div>
                        @endforelse
                    </div>
                    <!-- Botões de navegação (ajustados para novo tamanho) -->
                    <button @click="scroll(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/70 text-fuchsia-700 p-1 rounded-full shadow-sm hover:bg-white transition-all" aria-label="Anterior">‹</button>
                    <button @click="scroll(1)" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/70 text-fuchsia-700 p-1 rounded-full shadow-sm hover:bg-white transition-all" aria-label="Próximo">›</button>
                </div>
                <p class="text-[10px] text-gray-500 mt-1.5 italic">
                    ⚠️ Valores e estoque sujeitos a alteração no site oficial.
                </p>
            </div>

            <!-- Link do WhatsApp -->
            <div class="md:col-span-3 text-center">
                <h4 class="text-lg font-bold mb-3">Grupo de Ofertas</h4>
                <h4 class="text-s font-semibold text-gray-800 mb-1">
                    Quer economizar de verdade?
                </h4>
                <p class="text-gray-600 mb-2">
                    Receba as melhores ofertas!
                </p>
                <a href="{{ route('watsapp.click') }}" target="_blank" class="inline-flex items-center justify-center w-full sm:w-auto px-15 py-1 rounded-full font-bold text-white bg-green-500 hover:bg-green-600 transition-colors">
                    <!-- Ícone do WhatsApp -->
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.52 3.48A11.77 11.77 0 0012 0C5.37 0 0 5.37 0 12c0 2.1.54 4.16 1.57 5.97L0 24l6.2-1.6A11.93 11.93 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.84 0-3.62-.49-5.18-1.42l-.37-.22-3.68.95.98-3.58-.24-.37A9.94 9.94 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.95 9.95 0 0122 12c0 5.52-4.48 10-10 10zm5.23-7.77c-.29-.15-1.72-.85-1.99-.94-.27-.1-.47-.15-.67.15-.2.29-.77.94-.95 1.14-.18.2-.35.22-.64.07-.29-.15-1.22-.45-2.32-1.44-.86-.76-1.44-1.7-1.61-1.99-.17-.29-.02-.45.13-.6.13-.13.29-.35.44-.52.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.79.37-.27.29-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.1 4.49.71.31 1.26.49 1.69.63.71.23 1.35.2 1.86.12.57-.08 1.72-.7 1.96-1.37.24-.67.24-1.25.17-1.37-.06-.12-.26-.2-.55-.35z" />
                    </svg>
                    Clique aqui
                </a>
            </div>
        </div>
     <!-- Rodapé -->
<div class="border-t border-gray-300 mt-2 pt-4 text-center text-gray-600 text-xs">
    <div class="flex flex-wrap items-center justify-between gap-2 md:gap-4">
        <!-- ESQUERDA: Copyright -->
        <div>
            Copyright © {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Todos os direitos reservados.
        </div>

        <!-- CENTRO: Privacidade | Termos -->
        <div class="flex items-center gap-2">
            <a href="{{ route('site.about') }}" class="hover:text-pink-600 transition">Quem somos</a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('site.privacy') }}" class="hover:text-pink-600 transition">Privacidade</a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('site.terms') }}" class="hover:text-pink-600 transition">Termos</a>
        </div>

        <!-- DIREITA: Feito com ❤ por Visibilize -->
        <div class="flex items-center gap-1">
            Feito com <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 animate-pulse">❤ </span>por
            <a href="{{ route('visibilize.click') }}" target="_blank" class="hover:text-fuchsia-600 font-bold transition-colors">
                Visibilize
            </a>
        </div>
    </div>
</div>
    </div>
</footer>
