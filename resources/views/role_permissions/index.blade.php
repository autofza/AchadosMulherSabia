@extends('layouts.admin')

@section('content')
<!-- Título e Trilha de Navegação -->
<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">Permissão do Papel</h2>
        <nav class="breadcrumb">
            <a href="{{ route('dashboard.index') }}" class="breadcrumb-link">Dashboard</a>
            <span>/</span>
            <a href="{{ route('roles.index') }}" class="breadcrumb-link">Papéis</a>
            <span>/</span>
            <span>Lista de Permissões</span>
        </nav>
    </div>
</div>

<div class="content-box">
    <div class="content-box-header">
        <h3 class="content-box-title">Listar - {{ $role->name }}</h3>
        <div class="content-box-btn">
            @can('index-role')
            <a href="{{ route('roles.index') }}" class="btn-info align-icon-btn">
                <!-- Ícone queue-list (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
                <span>Listar</span>
            </a>
            @endcan
        </div>
    </div>

    <x-alert />

    <div class="table-container mt-6">
        <table class="table">
            <thead>
                <tr class="table-row-header">
                    <th class="table-header">ID</th>
                    <th class="table-header">Título</th>
                    <th class="table-header hidden lg:table-cell">Nome</th>
                    <th class="table-header center">Ações</th>
                </tr>
            </thead>
            <tbody>
                {{-- Imprimir os registros --}}
                @forelse ($permissions as $permission)
                <tr class="table-row-body">
                    <td class="table-body">{{ $permission->id }}</td>
                    <td class="table-body">{{ $permission->title }}</td>
                    <td class="table-body hidden lg:table-cell">{{ $permission->name }}</td>
                    <td class="table-actions">
                        <div class="table-actions-align">
                            @if (in_array($permission->id, $rolePermissions ?? []))
                            <a
                                href="{{ route('role-permissions.update', ['role' => $role->id, 'permission' => $permission->id]) }}">
                                <span style="color: #086;">Liberado</span>
                            </a>
                            @else
                            <a
                                href="{{ route('role-permissions.update', ['role' => $role->id, 'permission' => $permission->id]) }}">
                                <span style="color: #f00;">Bloqueado</span>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>

                @empty
                <div class="alert-warning">
                    Nenhum registro encontrado!
                </div>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection