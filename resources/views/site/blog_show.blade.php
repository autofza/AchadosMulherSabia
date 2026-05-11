@extends('layouts.site')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-5 flex flex-wrap gap-6">

    <!-- Main Content -->
    <div class="flex-1 md:flex-[3] bg-white p-5 rounded-lg shadow-sm">

        <h1 class="text-2xl font-bold text-gray-800 mb-3 text-center">{{ $post->title }}</h1>

        <div class="text-gray-500 text-sm mb-5">
            {{ $post->created_at->format('d M Y') }} • Atualizado em {{ $post->updated_at->format('d M Y') }}
        </div>

        <div class="text-center my-5">
            <img class="mx-auto w-full max-w-sm h-auto rounded-lg" src="{{ asset($post->image) }}" alt="{{ $post->title }}">
        </div>

        <div class="text-justify">
            {!! strip_tags(
            $post->content,
            '<p><br><b><i><strong><em>
                                <ul>
                                    <ol>
                                        <li><a>',
                                                ) !!}
        </div>
    </div>

    <!-- Sidebar -->
    <div class="flex-1 md:flex-[1] bg-white p-2 rounded-lg shadow-sm space-y-6">

        <!-- Related Posts -->
        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white p-1 rounded-lg text-center space-y-2">
            <div class="space-y-4 p-2 bg-white rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">LEIA MAIS</h3>

                <div class="space-y-3">
                    @foreach ($posts as $post)
                    <a href="{{ route('site.blog_show', $post->slug) }}" class="block border-b-2 border-dashed border-pink-600 pb-3 last:border-b-0 hover:bg-gradient-to-r hover:from-purple-500/20 hover:via-pink-500/20 hover:to-red-500/20 transition-colors duration-200">

                        <div class="flex items-start gap-3">
                            <img class="mx-auto w-full max-w-[80px] h-auto rounded-lg" src="{{ $post->image ? asset($post->image) : asset('images/default-blog.jpg') }}" alt="{{ $post->title }}">

                            <div class="flex-1">
                                <h4 class="text-gray-800 font-semibold mb-1 line-clamp-2">{{ $post->title }}</h4>
                                <p class="text-gray-500 text-sm">{{ $post->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Link do WhatsApp -->
        <div class="p-1 text-center">
            <h4 class="text-s font-semibold text-gray-800 mb-1">
                Quer economizar de verdade?
            </h4>
            <p class="text-gray-600 mb-2">
                Receba as melhores ofertas.
            </p>
            <a href="{{ route('watsapp.click') }}" target="_blank" class="inline-flex items-center justify-center w-full sm:w-auto px-15 py-1 rounded-full font-bold text-white bg-green-500 hover:bg-green-600 transition-colors">
                <!-- Ícone do WhatsApp -->
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.52 3.48A11.77 11.77 0 0012 0C5.37 0 0 5.37 0 12c0 2.1.54 4.16 1.57 5.97L0 24l6.2-1.6A11.93 11.93 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.19-3.48-8.52zM12 22c-1.84 0-3.62-.49-5.18-1.42l-.37-.22-3.68.95.98-3.58-.24-.37A9.94 9.94 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.95 9.95 0 0122 12c0 5.52-4.48 10-10 10zm5.23-7.77c-.29-.15-1.72-.85-1.99-.94-.27-.1-.47-.15-.67.15-.2.29-.77.94-.95 1.14-.18.2-.35.22-.64.07-.29-.15-1.22-.45-2.32-1.44-.86-.76-1.44-1.7-1.61-1.99-.17-.29-.02-.45.13-.6.13-.13.29-.35.44-.52.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.79.37-.27.29-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.1 4.49.71.31 1.26.49 1.69.63.71.23 1.35.2 1.86.12.57-.08 1.72-.7 1.96-1.37.24-.67.24-1.25.17-1.37-.06-.12-.26-.2-.55-.35z" />
                </svg>
                Clique aqui
            </a>
        </div>

        <!-- Promo Box -->
        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white p-1 rounded-lg text-center space-y-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition transform hover:-translate-y-0.5 flex flex-col">

                <!-- Imagem da promoção -->
                <!-- <a href="{{-- $product->link ?? '#' --}}" target="_blank" class="relative"> -->
                <a href="{{ route('product.click', $product->id) }}" target="_blank" class="relative">
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->title }}" class="w-full max-h-40 object-contain mx-auto">
                    @else
                        <img src="{{ asset('uploads/imgSem.jpg') }}" alt="{{ $product->title }}" class="w-full h-40 object-contain mx-auto">
                    @endif

                    <!-- Selo de desconto (fixo no canto superior esquerdo) -->
                    @php
                        $desconto = 0;
                    if ($product->original_price > 0 && $product->promo_price > 0) {
                        $desconto = round((($product->original_price - $product->promo_price) / $product->original_price) * 100,);
                    }
                    @endphp

                    @if ($desconto > 0)
                    <span class="absolute top-2 left-2 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500  text-white text-[11px] font-bold px-2 py-0.5 rounded shadow">
                        -{{ $desconto }}% Off
                    </span>
                    @endif
                </a>

                <!-- Conteúdo -->
                <div class="p-2 flex-1">
                    @if ($product->company && $product->company->soon)
                    <div class="flex items-center justify-between">
                        <img src="{{ asset($product->company->soon) }}" alt="{{ $product->company->name }}" class="w-6 h-6 rounded-full object-cover mr-1">
                        <p class="text-gray-400 text-xs">
                            Postado {{ $product->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    @endif

                    @if ($product->description)
                    <p class="text-gray-600 text-xs mb-1">
                        {{ Str::limit($product->description, 35) }}
                    </p>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 line-through">
                            R$ {{ number_format($product->original_price, 2, ',', '.') }}
                        </span>
                        <span class="text-sm font-bold text-purple-800">
                            R$ {{ number_format($product->promo_price, 2, ',', '.') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 italic">
                        ⚠️ Valores sujeitos a alteração no site.
                    </p>
                </div>

                <!-- Footer com botão -->
                <div class="bg-gray-50 p-2">
                    <a href="{{ route('product.click', $product->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-1.5 rounded-md font-bold bg-pink-600 hover:bg-pink-700 text-white text-xs">

                        <!-- Ícone de link externo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7m0 0v7m0-7L10 14" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v11h11" />
                        </svg>
                        {{ $product->company->name }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Companys Products -->
        <div>
            <h4 class="text-gray-800 font-semibold mb-2">Ver as melhores ofertas</h4>
            <div class="flex gap-3 mt-2">
                @foreach ($companys as $company)
                <a href="{{ route('site.byCompany', $company->slug) }}" class="flex flex-col items-center">
                    <div class="w-9 h-9 flex items-center justify-center bg-pink-600 rounded-full overflow-hidden">
                        <img src="{{ asset($company->soon) }}" alt="{{ $company->name }}" class="w-8 h-8 object-cover rounded-full">
                    </div>
                    <p class="text-xs text-gray-700 text-center mt-1">{{ $company->name }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
