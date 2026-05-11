<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        header {
            text-align: center;
            border-bottom: 2px solid #444;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 18px;
        }

        header p {
            margin: 0;
            font-size: 12px;
            color: #777;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            border-top: 1px solid #aaa;
            padding-top: 5px;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table th {
            width: 120px;
            background-color: #f2f2f2;
        }

        .label {
            font-weight: bold;
        }

    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <h1>{{ config('app.name') }}</h1>
    </header>

    @yield('content')

    <footer>
        <p>Gerado em {{ now()->format('d/m/Y H:i:s') }} - https://visibilize.com.br</p>
    </footer>

</body>
</html>
