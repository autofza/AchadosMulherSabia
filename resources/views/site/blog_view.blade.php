@extends('layouts.site')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-4">
    <!-- Título da Página -->
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
        Últimos Artigos do Blog
    </h1>

    <!-- Grid de posts -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($posts as $post)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-2 flex flex-col overflow-hidden group">
            <a href="{{ route('site.blog_show', $post) }}">
                <!-- Imagem com Overlay -->
                <div class="relative overflow-hidden">
                        <img src="{{ $post->image ? asset($post->image) : asset('images/default-blog.jpg') }}" alt="{{ $post->title }}" class="w-full h-48 object-contain bg-white transform transition duration-500 group-hover:scale-110 group-hover:brightness-110">
                        <div class="absolute inset-0 bg-black bg-opacity-20 transition opacity-0 group-hover:opacity-20"></div>
                </div>
            
                <!-- Conteúdo do Card -->
                <div class="p-5 flex flex-col flex-grow">
                    <h2 class="text-1xl font-bold text-gray-800 mb-2 text-justify">
                        {{ $post->title }}
                    </h2>
            
                    <p class="text-sm text-gray-600 mb-4 flex-grow text-justify">
                        {{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 90) }}
                    </p>
    
                    <!-- Footer com Autor, Data e Botão -->
                    <div class="mt-auto flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-gray-500 border-t border-gray-200 pt-3 gap-3">
                    
                        <!-- Autor e Data -->
                        <div class="flex flex-row justify-between items-center text-xs text-gray-500 gap-3">
                            <!-- Bloco do Autor -->
                            <div class="text-left">
                                <div class="text-xs text-gray-500 font-medium">✍️ Autor</div>
                                <div class="text-xs text-gray-500 tracking-tight">
                                    {{ config('app.name') }}
                                </div>
                            </div>
                        
                            <!-- Linha vertical de separação -->
                            <div class="w-px h-6 bg-gray-400"></div>
                        
                            <!-- Bloco da Data -->
                            <div class="text-right">
                                <div class="font-medium">🕒</div>
                                <div class="text-xs text-gray-500 tracking-tight">
                                    {{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Data não informada' }}
                                </div>
                            </div>
                        </div>
                    
                        <!-- Botão "Ler mais" -->
                        <a href="{{ route('site.blog_show', $post) }}" class="inline-flex items-center justify-center px-2 py-2 bg-pink-600 text-white text-sm font-medium rounded-lg hover:bg-pink-700 transition whitespace-nowrap">
                            Ler mais →
                        </a>
                    
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection