<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ClickEvent;

class PublicProductController extends Controller
{
    /**
     * Acesso público via slug
     * Ex: /p/marca-texto-grifpen
     */
    public function redirect(string $slug, Request $request)
    {
        Log::info('➡️ Acessando redirect do produto', [
            'slug' => $slug,
            'ip'   => $request->ip(),
        ]);

        $product = Product::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        Log::info('📦 Produto encontrado, encaminhando para product_click', [
            'product_id' => $product->id,
            'link'       => $product->link,
        ]);

        // 🔁 Chama o método oficial de clique
        return $this->product_click($product, $request);
    }

    /**
     * Clique oficial do produto
     * Ex: /click/product/{product}
     */
    public function product_click(Product $product, Request $request)
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        // 🤖 Lista de bots conhecidos
        $bots = [
            'googlebot',
            'bingbot',
            'semrush',
            'ahrefs',
            'facebookexternalhit',
            'crawler',
            'bot',
        ];

        foreach ($bots as $bot) {
            if (str_contains($userAgent, $bot)) {
                
                Log::info('🤖 Clique ignorado (BOT detectado)', [
                    'product_id' => $product->id,
                    'bot'        => $bot,
                    'user_agent' => $userAgent,
                    'ip'         => $request->ip(),
                ]);

                // ❌ Não registra clique
                // ❌ Não redireciona
                return response()->noContent();
            }
        }

        // 🧾 Log no banco (SOMENTE HUMANO)
       ClickEvent::create([
            'action'     => 'click',
            'product_id' => $product->id,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Log::info('🖱️ Clique HUMANO registrado', [
            'product_id' => $product->id,
            'ip'         => $request->ip(),
        ]);

        // 🔗 Redireciona direto para o afiliado
        return redirect()->away($product->link);
    }
}
