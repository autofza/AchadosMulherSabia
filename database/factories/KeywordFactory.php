<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KeywordFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->word();
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
