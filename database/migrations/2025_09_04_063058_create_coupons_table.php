<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Código único do cupom
            $table->string('code')->unique();

            // Valor do desconto ou benefício do cupom
            $table->string('value');

            // Relacionamento com companies (tabela companys)
            $table->foreignId('company_id')->constrained('companys')->onDelete('cascade');

            // Indica se o cupom está ativo
            $table->boolean('active')->default(true);

            // Link do cupom
            $table->string('link')->nullable(); // adicionado

            // Datas de criação e atualização
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
