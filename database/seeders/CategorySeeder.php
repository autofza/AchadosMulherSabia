<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categorys = [
            'Tecnologia',
            'Eletrodomésticos',
            'Beleza',
            'Esportes',
            'Moda',
            'Casa e Decoração',
        ];

        foreach ($categorys as $nome) {
            Category::firstOrCreate(
                ['name' => $nome],
                ['active' => true,]
            );
        }
    }
}
