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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // dados principais
            $table->string('title');
            $table->string('slug')->nullable(); // 👈 AJUSTE
            $table->string('image')->nullable();
            $table->string('link')->nullable();

            // chaves estrangeiras
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();

            // preços
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('promo_price', 10, 2)->nullable();

            // status e conteúdo
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);

            // data/hora
            $table->timestamp('inspired')->nullable();

            $table->timestamps();

            // constraints
            $table->foreign('category_id')
                ->references('id')->on('categorys')
                ->onDelete('set null');

            $table->foreign('company_id')
                ->references('id')->on('companys')
                ->onDelete('set null');

            $table->foreign('coupon_id')
                ->references('id')->on('coupons')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
