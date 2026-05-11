@extends('layouts.admin')

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Cupons</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('coupons.index') }}" class="breadcrumb-link">Cupons</a>
            <span>/</span>
            <span>Editar</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Editar o Cupom: {{ old('code', $coupon->code) }}</h3>
        <div class="content-box-btn">
            @can('index-coupon')
            <a href="{{ route('coupons.index') }}" class="btn-info align-icon-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />

    <form action="{{ route('coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
            <!-- code -->
            <div class="mb-2">
                <label for="code" class="form-label">Código</label>
                <input type="text" name="code" id="code" class="form-input" value="{{ old('code', $coupon->code) }}" required>
            </div>

            <!-- value -->
            <div class="mb-2">
                <label for="value" class="form-label">Valor</label>
                <input type="text" name="value" id="value" class="form-input" value="{{ old('value', $coupon->value) }}" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
            <!-- Link do Cupom -->
            <div class="mb-2">
                <label for="link" class="form-label">Link</label>
                <input type="url" name="link" id="link" class="form-input" placeholder="https://exemplo.com/cupom" value="{{ old('link', $coupon->link) }}">
            </div>

            <!-- Empresa -->
            <div class="mb-2">
                <label for="company_id" class="form-label">Empresa</label>
                <select name="company_id" id="company_id" class="form-input" required>
                    <option value="">Selecione uma empres</option>
                    @foreach ($companys as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $coupon->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
</div>

<!-- Status (Ativo) -->
<div class="mb-4">
    <label for="active" class="form-label inline-flex items-center">
        <!-- Hidden input envia 0 se o checkbox não estiver marcado -->
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" id="active" value="1" {{ old('active', $coupon->active) ? 'checked' : '' }} class="form-checkbox">
        Ativo
    </label>
</div>


<!-- Botão -->
<button type="submit" class="btn-success align-icon-btn">
    <span>Salvar Alterações</span>
</button>
</form>
</div>
@endsection

@push('scripts')
@endpushs
