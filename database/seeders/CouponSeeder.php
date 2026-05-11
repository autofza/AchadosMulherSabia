<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Cria 10 cupons de exemplo
        Coupon::factory()->count(10)->create();
    }
}
