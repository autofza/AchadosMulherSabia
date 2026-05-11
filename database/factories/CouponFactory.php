<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            // Código do cupom: COUPON123, etc.
            'code' => strtoupper($this->faker->unique()->bothify('COUPON###')),

            // Valor do cupom entre 5 e 100
            'value' => $this->faker->randomFloat(2, 5, 100),

            // Pega uma empresa existente ou cria uma nova se não houver
            'company_id' => Company::inRandomOrder()->first()->id ?? Company::factory()->create()->id,

            // 80% chance de estar ativo
            'active' => $this->faker->boolean(80),

            // Link do cupom
            'link' => $this->faker->optional()->url(), // adicionado
        ];
    }
}
