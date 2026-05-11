<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0; url={{ $url }}">
    <title>Redirecionando...</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            text-align: center;
            padding: 2rem;
            color: #333;
        }
        a {
            color: #1a73e8;
        }
    </style>
</head>
<body>
    <p>Redirecionando para <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">a página do produto</a>...</p>

    <script>
        // Força o redirecionamento em qualquer navegador, incluindo WebView
        window.location.replace("{{ $url }}");
    </script>
</body>
</html>
