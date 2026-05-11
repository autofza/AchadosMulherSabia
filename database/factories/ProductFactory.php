<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title'          => $this->faker->sentence(3),
            'image'          => $this->faker->imageUrl(640, 480, 'products', true),
            'link'           => $this->faker->url(),
            'category_id'    => Category::inRandomOrder()->value('id'),
            'company_id'     => Company::inRandomOrder()->value('id'),
            'coupon_id'      => Coupon::inRandomOrder()->value('id'),
            'original_price' => $this->faker->randomFloat(2, 50, 500),
            'promo_price'    => $this->faker->randomFloat(2, 20, 400),
            'description'    => $this->faker->paragraph(),
            'active'         => $this->faker->boolean(90), // 90% ativos
            'inspired'       => $this->faker->optional()->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
