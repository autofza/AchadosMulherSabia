@extends('layouts.login')

@section('content')
    <h1 class="title-login">Área Restrita</h1>

    <x-alert />

    <form class="mt-4" action="{{ route('login.process') }}" method="POST">
        @csrf
        @method('POST')

        <!-- Campo e-mail -->
        <div class="form-group-login">
            <label for="email" class="form-label-login">E-mail</label>
            <input type="email" name="email" id="email" placeholder="Digite o e-mail de usuário"
                class="form-input-login" value="{{ old('email') }}" required>
        </div>

        <!-- Campo senha -->
        <div class="form-group-login">
            <label for="password" class="form-label-login">Senha</label>
            <input type="password" name="password" id="password" placeholder="Digite a senha" class="form-input-login"
                value="{{ old('password') }}" required>
        </div>

        <!-- Link para recuperação de senha e botão de login -->
        <div class="btn-group-login">
            <button type="submit" class="btn-primary-md">Acessar</button>
        </div>
    </form>
@endsection
