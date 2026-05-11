<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('soon')->nullable(); // URL ou caminho da imagem
            $table->string('link')->nullable(); // link para a loja
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companys');
    }
};
