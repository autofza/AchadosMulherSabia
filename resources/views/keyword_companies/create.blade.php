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
            <span>Cadastrar</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Cadastrar</h3>
        <div class="content-box-btn">
            {{-- Permissão Ajustada --}}
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

    {{-- Rota Ajustada --}}
    <form action="{{ route('keyword_companies.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="mb-4">
            <label for="name" class="form-label">Descrição</label>
            <input type="text" name="name" id="name" class="form-input"
                placeholder="Ex: Consultoria Financeira" value="{{ old('name') }}" required>
        </div>

        {{-- Select de EMPRESA (Alterado de category_id para company_id) --}}
        <div class="mb-4">
            <label for="company_id" class="form-label">Empresa</label>
            <select name="company_id" id="company_id" class="form-input" required>
                <option value="">Selecione uma empresa</option>
                {{-- Variável $companies vem do Controller --}}
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-success align-icon-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Cadastrar</span>
        </button>
    </form>
</div>
@endsection