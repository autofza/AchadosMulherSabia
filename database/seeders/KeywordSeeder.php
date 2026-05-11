<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Keyword;
use Illuminate\Support\Str;

class KeywordSeeder extends Seeder
{
    public function run(): void
    {
        $categoryKeywords = [
            'Eletrônicos' => [
                'celular', 'fone', 'carregador', 'notebook', 'usb', 'bluetooth',
                'smartwatch', 'tablet', 'tv', 'monitor', 'teclado', 'mouse'
            ],
            'Eletrodomésticos' => [
                'liquidificador', 'cafeteira', 'micro-ondas', 'geladeira', 'batedeira',
                'aspirador', 'ventilador', 'ferro de passar', 'air fryer', 'lava-louça',
                'panela elétrica', 'sanduicheira', 'cooktop', 'indução'
            ],
            'Casa e Decoração' => [
                'sofá', 'tapete', 'almofada', 'almofadas', 'quadro', 'cadeira', 'luminária', 'lâmpadas', 'lustre', 'pendente',
                'cortina', 'decoração', 'lençol', 'edredom', 'guardanapo', 'organizador', 'mesa', 'banqueta', 'taças', 'porta tempero', 'facas', 'bandeja'
            ],
            'Beleza e Saúde' => [
                'perfume', 'creme', 'shampoo', 'hidratação', 'escova', 'maquiagem',
                'secador', 'barbeador', 'chapinha', 'hidratante', 'loção'
            ],
            'Moda' => [
                'camisa', 'calça', 'blusa', 'vestido', 'sapato', 'bolsa',
                'relógio', 'jaqueta', 'shorts', 'saia', 'óculos', 'mochila', 'sandália', 'chinelo', 'rasteirinha'
            ],
            'Brinquedos' => [
                'lego', 'boneca', 'carrinho', 'brinquedo', 'pelúcia', 'quebra-cabeça'
            ],
            'Esporte e Lazer' => [
                'bicicleta', 'bola', 'tênis', 'academia', 'mochila', 'caminhada',
                'halter', 'barraca', 'patins'
            ],
            'Ferramentas' => [
                'furadeira', 'parafusadeira', 'chave de fenda', 'alicate',
                'martelo', 'serra', 'lixadeira', 'nivel', 'trena', 'multímetro'
            ],
            'Pet Shop' => [
                'ração', 'gato', 'cachorro', 'brinquedo pet', 'comedouro', 'areia sanitária',
                'caminha', 'coleira', 'petisco'
            ],
        ];

        foreach ($categoryKeywords as $categoryName => $keywords) {
            $category = Category::where('name', 'like', "%{$categoryName}%")->first();

            if (!$category) {
                echo "⚠️ Categoria não encontrada: {$categoryName}\n";
                continue;
            }

            foreach ($keywords as $name) {
                Keyword::updateOrCreate(
                    [
                        'name' => $name,
                        'category_id' => $category->id, // <-- agora faz parte da chave única
                    ],
                    [
                        'slug' => Str::slug($name),
                    ]
                );
            }
        }

        echo "✅ Keywords cadastradas com sucesso!\n";
    }
}
