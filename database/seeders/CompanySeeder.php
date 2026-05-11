<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        try {
            $companys = [
                [
                    'name' => 'Magazine Luiza',
                    'soon' => 'https://exemplo.com/soons/magalu.png',
                    'link' => 'https://www.magazineluiza.com.br',
                ],
                [
                    'name' => 'Mercado Livre',
                    'soon' => 'https://exemplo.com/soons/mercadolivre.png',
                    'link' => 'https://www.mercadolivre.com.br',
                ],
                [
                    'name' => 'Amazon',
                    'soon' => 'https://exemplo.com/soons/amazon.png',
                    'link' => 'https://www.amazon.com.br',
                ],
                [
                    'name' => 'Shopee',
                    'soon' => 'https://exemplo.com/soons/shopee.png',
                    'link' => 'https://shopee.com.br',
                ],
            ];

            foreach ($companys as $company) {
                Company::firstOrCreate(
                    ['name' => $company['name']], // condição única
                    $company                      // dados para criar se não existir
                );
            }

        } catch (\Exception $e) {
            Log::error('Erro ao executar CompanySeeder: ' . $e->getMessage());
        }
    }
}
