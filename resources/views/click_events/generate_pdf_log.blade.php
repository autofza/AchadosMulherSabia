@extends('layouts.pdf')

@section('content')

<h3>Registro do Log nº {{ $log->id }}</h3>

<table>
    <tr>
        <th class="label">Tipo de Acesso</th>
        <td>{{ $log->action }}</td>
    </tr>
    <tr>
        <th class="label">Produto</th>
        <td>{{ $log->product ? $log->product->title : '-' }}</td>
    </tr>
    <tr>
        <th class="label">Cupom</th>
        <td>{{ $log->coupon ? $log->coupon->code : '-' }}</td>
    </tr>
    <tr>
        <th class="label">IP</th>
        <td>{{ $log->ip ?? '-' }}</td>
    </tr>
    <tr>
        <th class="label">User Agent</th>
        <td>{{ $log->user_agent ?? '-' }}</td>
    </tr>
    <tr>
        <th class="label">Cadastrado em</th>
        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
    </tr>
    <tr>
        <th class="label">Editado em</th>
        <td>{{ \Carbon\Carbon::parse($log->updated_at)->format('d/m/Y H:i:s') }}</td>
    </tr>
</table>
@endsection