@extends('layouts.admin')

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Categoria</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('categories.index') }}" class="breadcrumb-link">Categorias</a>
            <span>/</span>
            <span>Cadastrar</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Cadastrar</h3>
        <div class="content-box-btn">
            @can('index-category')
            <a href="{{ route('categories.index') }}" class="btn-info align-icon-btn">
                <!-- Ícone queue-list (Heroicons) -->
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
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="mb-4">
            <label for="name" class="form-label">Descrição</label>
            <input type="text" name="name" id="name" class="form-input" placeholder="Descrição da categoria" value="{{ old('name') }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Status: </label>
            <div>
                <input type="radio" name="active" id="active_1" value="1" {{ (old('active') ?? '1') == 1 ? 'checked' : '' }} required>
                <label for="active_1" class="form-input-checkbox">Ativo</label>

                <input type="radio" name="active" id="active_0" value="0" {{ old('active') == '0' ? 'checked' : '' }} required>
                <label for="active_0" class="form-input-checkbox">Inativo</label>
            </div>
        </div>

        {{-- Caso existam campos específicos para categoria, adicionar aqui --}}

        {{-- Se aplicar algum nível de permissão ou opções relacionadas a categorias, ajustar aqui --}}

        <button type="submit" class="btn-success align-icon-btn">
            <!-- Ícone plus-circle (Heroicons) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Cadastrar</span>
        </button>
    </form>
</div>
@endsection