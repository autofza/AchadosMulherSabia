<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        $category = Category::first() ?? Category::create([
            'name' => 'Geral',
            'active' => 'Ativo',
        ]);

        $blogs = [
            [
                'title' => 'Bem-vindo ao nosso Blog!',
                'slug' => 'bem-vindo-ao-nosso-blog',
                'content' => 'Este é o primeiro post do nosso blog, criado automaticamente via seeder.',
                'image' => null,
                'published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Dicas de Promoções',
                'slug' => 'dicas-de-promocoes',
                'content' => 'Confira as melhores promoções da semana em nossa plataforma.',
                'image' => null,
                'published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}