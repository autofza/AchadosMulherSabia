@extends('layouts.pdf')

@section('content')
    <h2 style="text-align: center;">Usuários</h2>

    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #adb5db">
                <th style="border: 1px solid #ccc;">ID</th>
                <th style="border: 1px solid #ccc;">Nome</th>
                <th style="border: 1px solid #ccc;">E-mail</th>
                <th style="border: 1px solid #ccc;">Cadastrado em</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <th style="border: 1px solid #ccc; border-top: none;">{{ $user->id }}</th>
                    <th style="border: 1px solid #ccc; border-top: none;">{{ $user->name }}</th>
                    <th style="border: 1px solid #ccc; border-top: none;">{{ $user->email }}</th>
                    <th style="border: 1px solid #ccc; border-top: none;">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s') }}</th>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <div class="alert-warning">
                            Nenhum registro encontrado!
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
