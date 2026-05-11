@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">Blog</h2>
            <nav class="breadcrumb">
                <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
                <span>/</span>
                <a href="{{ route('blogs.index') }}" class="breadcrumb-link">Blogs</a>
                <span>/</span>
                <span>Editar</span>
            </nav>
        </div>
    </div>

    <div class="content-box">
        <div class="content-box-header">
            <h3 class="content-box-title">Editar</h3>
            <div class="content-box-btn">
                @can('index-blog')
                    <a href="{{ route('blogs.index') }}" class="btn-info align-icon-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                        </svg>
                        <span>Listar</span>
                    </a>
                @endcan
            </div>
        </div>

        <x-alert />

        <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <!-- Título -->
            <div class="mb-4">
                <label for="title" class="form-label">Título</label>
                <input type="text" name="title" id="title" class="form-input" placeholder="Título do blog"
                    value="{{ old('title', $blog->title) }}" required>
            </div>

            <!-- Conteúdo -->
            <div class="mb-4">
                <label for="content" class="form-label">Conteúdo</label>

                <!-- Input hidden que vai receber o HTML do Quill -->
                <input type="hidden" name="content" id="content">

                <!-- Editor Quill -->
                <div id="editor" class="form-input w-full border p-2 rounded" style="height: 300px;">
                    {!! old('content', $blog->content) !!}
                </div>
            </div>

            <!-- Imagem -->
            <div class="mb-4">
                <label for="image" class="form-label">Imagem</label>
                <input type="file" name="image" id="image" class="form-input">
                @if ($blog->image)
                    <div class="mt-2">
                        <p>Imagem atual:</p>
                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            <!-- Publicado -->
            <div class="mb-4">
                <label for="published" class="form-label">Publicado?</label>
                <select name="published" id="published" class="form-input">
                    <option value="0" {{ old('published', $blog->published) == 0 ? 'selected' : '' }}>Não</option>
                    <option value="1" {{ old('published', $blog->published) == 1 ? 'selected' : '' }}>Sim</option>
                </select>
            </div>

            <!-- Data de Publicação -->
            <div class="mb-4">
                <label for="published_at" class="form-label">Data de Publicação</label>
                <input type="datetime-local" name="published_at" id="published_at" class="form-input"
                    value="{{ old('published_at', $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d\TH:i') : '') }}">
            </div>

            <!-- Botão -->
            <button type="submit" class="btn-warning align-icon-btn">
                <span>Salvar</span>
            </button>
        </form>
    </div>
@endsection

@push('styles')
    <!-- Quill CSS via CDN -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Quill JS via CDN -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Digite o conteúdo do blog...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'header': 1 }, { 'header': 2 }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image']
                    ]
                }
            });

            // Antes de enviar, copia o conteúdo do Quill para o input hidden
            document.querySelector('form').addEventListener('submit', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        });
    </script>
@endpush
