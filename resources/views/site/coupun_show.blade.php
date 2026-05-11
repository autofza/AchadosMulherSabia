@extends('layouts.site')

@section('content')

<!-- Cupom Personalizado -->
<div class="flex items-center justify-between p-5 max-w-md mx-auto bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 rounded-2xl shadow-xl text-white font-sans gap-4">
    <!-- Ícone do cupom -->
    <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-white bg-opacity-25 rounded-full text-3xl">
        🎁
    </div>

    <!-- Detalhes do cupom -->
    <div class="flex-1">
        <div class="text-xl font-extrabold mb-1 drop-shadow-lg">
            50% OFF Especial
        </div>
        <div class="text-sm opacity-90 mb-2">
            Em produtos selecionados
        </div>
        <div class="text-xs opacity-80 mb-2">
            Compra mínima R$ 499,00<br>Limite de R$ 250
        </div>
        <div class="text-xs opacity-70 italic">
            Vence 15 de outubro
        </div>
    </div>

    <!-- Botão -->
    <button class="bg-white text-purple-600 font-bold rounded-xl px-5 py-2 hover:bg-purple-50 active:scale-95 transition-all shadow-md">
        Aplicar
    </button>
</div>

@endsection