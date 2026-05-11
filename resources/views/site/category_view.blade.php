@extends('layouts.site')

@section('content')
<div class="max-w-6xl mx-auto mt-1 mb-8 p-4 bg-white rounded-lg shadow-sm text-center">
    <!-- Cabeçalho da Loja -->
    <div class="flex items-center p-2 border-b">
        <div class="flex items-center justify-between w-full">
            <div class="max-w-6xl mx-auto px-4 py-2 text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Categorias de Produtos</h1>
                <p class="text-sm text-gray-600">Aproveite os melhores descontos das nossas empresas parceiras!</p>
            </div>
        </div>
    </div> 
    <div class="mx-auto px-4 py-6">
        <!-- Categorias em Carrossel -->
        <div x-data="carousel" x-init="init(); startAutoSlide()" @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()" class="relative overflow-hidden">
            <!-- Faixa rolável -->
            <div x-ref="carousel" class="flex gap-6 overflow-x-auto scroll-smooth pb-4 hide-scrollbar">
                @foreach($categorys as $category)
                
                
                <!-- Mudar aqui em baixo o link ! -->
                
                
                <a href="{{-- route('site.category', $category->slug) --}}" class="flex flex-col items-center text-center group cursor-pointer min-w-[100px]">
                    <div class="w-30 h-30 rounded-full p-[2px] bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 flex items-center justify-center shadow-sm group-hover:shadow-md transition">
                        <div class="w-full h-full rounded-full bg-white overflow-hidden flex items-center justify-center">
                            <img src="{{ asset($category->products->first()->image ?? 'uploads/imgSem.jpg') }}" alt="{{ $category->name }}" class="w-28474849 h-28 object-cover rounded-full">
                        </div>
                    </div>
                    <span class="mt-2 text-sm text-gray-700 group-hover:text-purple-500/80 transition">
                        {{ $category->name }}
                    </span>
                </a>

                @endforeach
            </div>

            <!-- Botões -->
            <button @click="scroll(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 bg-gradient-to-r from-purple-500/80 via-pink-500/80 to-red-500/80 backdrop-blur-md text-white p-2 rounded-full 
       transition hover:scale-110">
                ‹
            </button>
            <button @click="scroll(1)" class="absolute right-0 top-1/2 -translate-y-1/2 bg-gradient-to-r from-purple-500/80 via-pink-500/80 to-red-500/80 backdrop-blur-md text-white p-2 rounded-full 
           transition hover:scale-110 hover:from-purple-600/90 hover:via-pink-600/90 hover:to-red-600/90">
                ›
            </button>
        </div>
    </div>
</div>
<!-- Link do WhatsApp -->
<div class="max-w-6xl mx-auto mt-0 mb-8 p-4 bg-white rounded-lg shadow-sm text-center">
    <h4 class="text-lg font-semibold text-gray-800 mb-1">
         Quer economizar de verdade?
    </h4>
    <p class="text-gray-600 mb-4">
         Receba no WhatsApp as melhores ofertas e economize mais todo dia.
    </p>
    <a href="{{ route('watsapp.click') }}" target="_blank"
        class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-2 rounded-full font-bold text-white bg-green-500 hover:bg-green-600 transition-colors">
        <!-- Ícone do WhatsApp -->
        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.52 3.48A11.77 11.77 0 0012 0C5.37 0 0 5.37 0 12c0 2.1.54 4.16 1.57 5.97L0 24l6.2-1.6A11.93 11.93 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.84 0-3.62-.49-5.18-1.42l-.37-.22-3.68.95.98-3.58-.24-.37A9.94 9.94 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.95 9.95 0 0122 12c0 5.52-4.48 10-10 10zm5.23-7.77c-.29-.15-1.72-.85-1.99-.94-.27-.1-.47-.15-.67.15-.2.29-.77.94-.95 1.14-.18.2-.35.22-.64.07-.29-.15-1.22-.45-2.32-1.44-.86-.76-1.44-1.7-1.61-1.99-.17-.29-.02-.45.13-.6.13-.13.29-.35.44-.52.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.79.37-.27.29-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.1 4.49.71.31 1.26.49 1.69.63.71.23 1.35.2 1.86.12.57-.08 1.72-.7 1.96-1.37.24-.67.24-1.25.17-1.37-.06-.12-.26-.2-.55-.35z" />
        </svg>
        Clique aqui
    </a>
</div>
@endsection
