@extends('layouts.admin')

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Usuário</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('users.index') }}" class="breadcrumb-link">Usuários</a>
            <span>/</span>
            <span>Cadastrar</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Cadastrar</h3>
        <div class="content-box-btn">
            @can('index-user')
            <a href="{{ route('users.index') }}" class="btn-info align-icon-btn">
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
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
            <div class="mb-2">
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="Nome completo do usuário" value="{{ old('name') }}" required>
            </div>

            <div class="mb-2">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" name="email" id="email" class="form-input" placeholder="Melhor e-mail do usuário" value="{{ old('email') }}" required>
            </div>
        </div>
        <div class="mb-2">
            @can('edit-roles-user')
            <label class="form-label">Papel: </label>
            @forelse ($roles as $role)
            @if ($role != 'Super Admin' || Auth::user()->hasRole('Super Admin'))
            <input type="checkbox" name="roles[]" id="role_{{ Str::slug($role) }}" value="{{ $role }}" {{ collect(old('roles'))->contains($role) ? 'checked' : '' }}>
            <label for="role_{{ Str::slug($role) }}" class="form-input-checkbox">{{ $role }}</label>
            @endif
            @empty
            <p>Nenhum papel disponível.</p>
            @endforelse
            @endcan
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
            <div class="mb-2">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="Senha com no mínimo 6 caracteres" value="{{ old('password') }}" required>
            </div>

            <div class="mb-2">
                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Confirmar a senha" value="{{ old('password_confirmation') }}" required>
            </div>
        </div>
        <button type="submit" class="btn-success align-icon-btn">
            <!-- Ícone plus-circle (Heroicons) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Cadastrar</span>
        </button>

    </form>

</div>
@endsection
