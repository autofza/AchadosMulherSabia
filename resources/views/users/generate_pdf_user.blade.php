 @extends('layouts.pdf')

@section('content')

<h3>Usuário nº {{ $user->id }}</h3>

<table>
    <tr>
        <th class="label">Nome</th>
        <td>{{ $user->name }}</td>
    </tr>
    <tr>
        <th class="label">E-mail</th>
        <td>{{ $user->email }}</td>
    </tr>
    <tr>
        <th class="label">Cadastrado em</th>
        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s') }}</td>
    </tr>
    <tr>
        <th class="label">Editado em</th>
        <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i:s') }}</td>
    </tr>
</table>
@endsection