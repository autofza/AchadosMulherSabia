<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redirecionando...</title>

    <meta name="robots" content="noindex,nofollow">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
        }

        .spinner {
            margin: 20px auto;
            width: 40px;
            height: 40px;
            border: 4px solid #eee;
            border-top: 4px solid #e91e63;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #e91e63;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>🛍 {{ $product->title }}</h2>

    <p>Você está sendo redirecionado para o site do parceiro.</p>

    <p><strong>Aguarde {{ $delay }} segundos…</strong></p>
    <div class="spinner"></div>

    <noscript>
        <p>Clique abaixo se não for redirecionado:</p>
        <a href="{{ $redirectUrl }}">Ir para o produto</a>
    </noscript>
</div>

@if($delay > 0)
<script>
    setTimeout(function () {
        window.location.href = "{{ $redirectUrl }}";
    }, {{ $delay * 1000 }});
</script>
@endif

</body>
</html>
