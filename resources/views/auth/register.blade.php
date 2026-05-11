@extends('layouts.login')

@section('content')
    <h1 class="title-login">Cadastrar Usuário</h1>

    <x-alert />

    <form class="mt-4" action="{{ route('register.store') }}" method="POST">
        @csrf
        @method('POST')

        <!-- Campo name -->
        <div class="form-group-login">
            <label for="name" class="form-label-login">Nome</label>
            <input type="text" name="name" id="name" placeholder="Digite o nome completo" class="form-input-login"
                value="{{ old('name') }}" required>
        </div>

        <!-- Campo e-mail -->
        <div class="form-group-login">
            <label for="email" class="form-label-login">E-mail</label>
            <input type="email" name="email" id="email" placeholder="Digite o seu melhor e-mail"
                class="form-input-login" value="{{ old('email') }}" required>
        </div>

        <!-- Campo senha -->
        <div class="form-group-login">
            <label for="password" class="form-label-login">Senha</label>
            <input type="password" name="password" id="password" placeholder="Senha com no mínimo 6 caracteres" class="form-input-login"
                value="{{ old('password') }}" required>
        </div>

        <!-- Campo confirmar senha -->
        <div class="form-group-login">
            <label for="password_confirmation" class="form-label-login">Confirmar Senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmar a senha" class="form-input-login"
                value="{{ old('password_confirmation') }}" required>
        </div>

        <!-- Link para página de login -->
        <div class="btn-group-login">
            <a href="{{ route('login') }}" class="link-login">Login</a>
            <button type="submit" class="btn-primary-md">Cadastrar</button>
        </div>
    </form>
@endsection