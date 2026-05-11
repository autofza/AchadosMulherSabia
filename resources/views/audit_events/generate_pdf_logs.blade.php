@extends('layouts.pdf')

@section('content')
    <h2 style="text-align: center; margin-bottom: 15px;">Registros de Logs</h2>

    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #adb5db">
                <th style="border: 1px solid #ccc; width: 5%;">ID</th>
                <th style="border: 1px solid #ccc; width: 15%;">Tipo de Acesso</th>
                <th style="border: 1px solid #ccc; width: 20%;">Produto</th>
                <th style="border: 1px solid #ccc; width: 15%;">Cupom</th>
                <th style="border: 1px solid #ccc; width: 15%;">IP</th>
                <th style="border: 1px solid #ccc; width: 20%;">User Agent</th>
                <th style="border: 1px solid #ccc; width: 10%;">Cadastrado em</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td style="border: 1px solid #ccc; border-top: none;">{{ $log->id }}</td>
                    <td style="border: 1px solid #ccc; border-top: none;">{{ $log->action }}</td>
                    <td style="border: 1px solid #ccc; border-top: none;">
                        {{ $log->product ? $log->product->title : '-' }}
                    </td>
                    <td style="border: 1px solid #ccc; border-top: none;">
                        {{ $log->coupon ? $log->coupon->code : '-' }}
                    </td>
                    <td style="border: 1px solid #ccc; border-top: none;">{{ $log->ip ?? '-' }}</td>
                    <td style="border: 1px solid #ccc; border-top: none;">{{ $log->user_agent ?? '-' }}</td>
                    <td style="border: 1px solid #ccc; border-top: none;">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div style="padding: 10px; background-color: #fff3cd; color: #856404; text-align: center; border: 1px solid #ffeeba;">
                            Nenhum registro encontrado!
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
