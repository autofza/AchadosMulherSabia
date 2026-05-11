<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            // Corrigido: referencia a tabela 'categorys'
            $table->foreignId('category_id')
                ->constrained('categorys')
                ->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();

            // Garante que não existam keywords duplicadas para a mesma categoria
            $table->unique(['name', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
