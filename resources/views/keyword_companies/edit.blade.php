@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Palavra-chave (Empresa)</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('keyword_companies.index') }}" class="breadcrumb-link">Palavras-chave</a>
            <span>/</span>
            <span>Editar</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Editar Registro: {{ $keyword->id }}</h3>
        <div class="content-box-btn">
            {{-- Permissão correta --}}
            @can('index-keyword-company')
            <a href="{{ route('keyword_companies.index') }}" class="btn-info align-icon-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />
    
    {{-- Rota correta --}}
    <form action="{{ route('keyword_companies.update', $keyword) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="form-label">Descrição</label>
            <input type="text" name="name" id="name" class="form-input" 
                placeholder="Descrição da palavra-chave" 
                value="{{ old('name', $keyword->name) }}" required>
        </div>

        {{-- Select de EMPRESA --}}
        <div class="mb-4">
            <label for="company_id" class="form-label">Empresa</label>
            <select name="company_id" id="company_id" class="form-input" required>
                <option value="">Selecione uma empresa</option>
                {{-- Variável $companies passada pelo Controller --}}
                @foreach($companies as $company)
                    <option value="{{ $company->id }}"
                        {{ old('company_id', $keyword->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-warning align-icon-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
            <span>Salvar</span>
        </button>
    </form>
</div>
@endsection