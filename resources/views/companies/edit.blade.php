@extends('layouts.admin')

@section('content')

<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Empresa</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('companies.index') }}" class="breadcrumb-link">Empresas</a>
            <span>/</span>
            <span>Editar</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Editar</h3>
        <div class="content-box-btn">
            @can('index-company')
            <a href="{{ route('companies.index') }}" class="btn-info align-icon-btn">
                <!-- Ícone queue-list (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />

    <form action="{{ route('companies.update', ['company' => $company]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Título -->
        <div class="mb-4">
            <label for="name" class="form-label">Descrição</label>
            <input type="text" name="name" id="name" class="form-input"
                placeholder="Descrição da Empresa" value="{{ old('name', $company->name) }}" required>
        </div>

        <!-- l
         Link -->
        <div class="mb-4">
            <label for="link" class="form-label">Link do Site</label>
            <input type="text" name="link" id="link" class="form-input" placeholder="Link do Site" value="{{ old('link', $company->link) }}" required>
        </div>

        <!-- Imagem -->
        <div class="mb-4">
            <label for="soon" class="form-label">Imagem</label>
            <input type="file" name="soon" id="soon" class="form-input">
            @if($company->soon)
            <div class="mt-2">
                <p>Imagem atual:</p>
                @if($company->soon)
                <img src="{{ asset($company->soon) }}" alt="Logo da Empresa" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;"><br>
                @else
                <img src="{{ asset('uploads/imgSem.jpg') }}" alt="Sem logo" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;"><br>
                @endif
            </div>
            @endif
        </div>

        <!-- Botão -->
        <button type="submit" class="btn-warning align-icon-btn">
            <span>Salvar</span>
        </button>
    </form>
</div>
@endsection