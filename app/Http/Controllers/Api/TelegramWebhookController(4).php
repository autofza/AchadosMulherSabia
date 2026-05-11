<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\AuditsControllerActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use CURLFile;
use App\Models\Product;
use App\Models\Company;
use App\Models\Category;
use App\Models\KeywordCompany;
use App\Models\KeywordProduct;

class TelegramWebhookController extends Controller
{
    use AuditsControllerActions;
    
    public function handle(Request $request)
    {
        Log::info('📥 Webhook Telegram recebido', $request->all());
        
        $updateId = $request->input('update_id');
        
        if ($updateId && cache()->has('telegram_update_' . $updateId)) {
            Log::warning('🔁 Update já processado, ignorando', [
                'update_id' => $updateId
            ]);
        
            return response()->json(['ok' => true], 200);
        }
        
        if ($updateId) {
            cache()->put('telegram_update_' . $updateId, true, now()->addMinutes(10));
        }


        $this->audit(
            event: 'telegram.webhook.received',
            new: $request->all(),
            tags: ['telegram', 'webhook']
        );

        $message = $request->input('message');

        if (!$message) {
            return response()->json(['message' => 'Sem mensagem no payload'], 200);
        }

        $text = $message['text'] ?? $message['caption'] ?? null;

        if (!$text) {
            return response()->json(['message' => 'Sem texto ou legenda para processar'], 200);
        }

        preg_match('/https?:\/\/\S+/', $text, $matches);
        $link = $matches[0] ?? null;

        if (!$link) {
            return response()->json(['message' => 'Nenhum link encontrado'], 200);
        }

        $photo = $message['photo'] ?? [];
        $imageUrl = null;

        if (!empty($photo)) {
            $fileId = end($photo)['file_id'];
            $imageUrl = $this->getTelegramFileUrl($fileId);
        }

        $internalRequest = new Request([
            'titulo' => Str::of($text)->after('🛍')->before("\n")->trim()->value(),
            'preco_original' => $this->extractPrice($text, 'original'),
            'preco_promocional' => $this->extractPrice($text, 'promo'),
            'link_compra' => $link,
            'imagem_url' => $imageUrl,
            'description' => $text,
        ]);

        $internalRequest->headers->set(
            'Authorization',
            'Bearer ' . env('AUTOLINK_SECRET')
        );

        //return $this->receiveFromTelegram($internalRequest);
        try {
                $this->receiveFromTelegram($internalRequest);
            } catch (\Throwable $e) {
                Log::error('❌ Erro ao processar Telegram internamente', [
                    'error' => $e->getMessage(),
                ]);
            }
            
            // 🚨 IMPORTANTE: Telegram SEMPRE recebe 200
            return response()->json(['ok' => true], 200);

    }

    /**
     * =====================================================
     * 📥 RECEBE PRODUTO DO TELEGRAM
     * =====================================================
     */
    public function receiveFromTelegram(Request $request)
    {
        Log::info('🔥 receiveFromTelegram FOI CHAMADO');

        if ($request->header('Authorization') !== 'Bearer ' . env('AUTOLINK_SECRET')) {
            Log::warning('⛔ receiveFromTelegram: token inválido');
            return null;
        }


        $validated = $request->validate([
            'titulo'            => 'required|string|max:255',
            'preco_original'    => 'nullable|string',
            'preco_promocional' => 'nullable|string',
            'link_compra'       => 'required|url',
            'imagem_url'        => 'nullable|url',
            'description'       => 'nullable|string|max:1000',
        ]);

        Log::info('🔎 Iniciando validações de marketplace (BD)', [
            'titulo' => $validated['titulo'],
            'link'   => $validated['link_compra'],
        ]);

        try {
            $normalizeTitle = fn($t) =>
                mb_strtolower(trim(preg_replace('/\s+/', ' ', $t)));

            $convertPrice = function (?string $price): ?float {
                if (!$price) return null;
                $clean = str_replace(',', '.', preg_replace('/[^\d,]/', '', $price));
                return is_numeric($clean) ? (float)$clean : null;
            };

            $originalPrice = $convertPrice($validated['preco_original']);
            $promoPrice    = $convertPrice($validated['preco_promocional']);
            $inspiredDate  = Carbon::now()->addDays(Product::ACTIVE_DAYS);

            $rawLink   = trim($validated['link_compra']);
            $normTitle = $normalizeTitle($validated['titulo']);

            $company = $this->validateMarketplaceFromDatabase($rawLink);
            
            if (!$company) {
                Log::warning('⛔ Produto rejeitado: marketplace não cadastrado no BD', [
                    'titulo' => $validated['titulo'],
                    'link'   => $rawLink,
                ]);
            
                return null;
            }

            $imagePath = $validated['imagem_url']
                ? $this->downloadImage($validated['imagem_url'])
                : null;

            $cleanLink = str_replace(['https://', 'http://', 'www.'], '', strtolower($rawLink));

            $existingProduct = Product::where(function ($q) use ($cleanLink, $normTitle) {
                $q->whereRaw("LOWER(REPLACE(link, 'www.', '')) LIKE ?", ['%' . $cleanLink . '%'])
                  ->orWhereRaw('LOWER(TRIM(title)) = ?', [$normTitle]);
            })->first();

            Log::info('📦 Produto recebido do Telegram', [
                'titulo' => $validated['titulo'],
                'link' => $rawLink,
            ]);

            /**
             * =====================================================
             * 🔁 PRODUTO EXISTENTE → ATUALIZA + REENVIA
             * =====================================================
             */
            if ($existingProduct) {

                $existingProduct->update([
                    'original_price' => $originalPrice,
                    'promo_price'    => $promoPrice,
                    'inspired'       => $inspiredDate,
                    'company_id'     => $company?->id ?? $existingProduct->company_id,
                    'link'           => $rawLink,
                    'active'         => true,
                ]);

                Log::info('♻️ Produto atualizado e reativado', [
                    'product_id' => $existingProduct->id,
                ]);

                // 🔥 AGORA ENVIA TAMBÉM
                $this->sendToTelegram($existingProduct, $existingProduct->image);

                return response()->json([
                    'message' => 'Produto atualizado e reenviado ao Telegram',
                    'product_id' => $existingProduct->id,
                ], 200);
            }

            /**
             * =====================================================
             * 🆕 NOVO PRODUTO
             * =====================================================
             */
            $category = $this->identifyCategoryFromText(
                $validated['titulo'],
                $validated['description']
            );

            $product = Product::create([
                'title' => $validated['titulo'],
                'link' => $rawLink,
                'original_price' => $originalPrice,
                'promo_price' => $promoPrice,
                'image' => $imagePath ?? 'uploads/imgSem.jpg',
                'category_id' => $category?->id ?? 11,
                'company_id' => $company?->id,
                'description' => $validated['titulo'],
                'active' => true,
                'inspired' => $inspiredDate,
            ]);

            $this->audit(
                event: 'telegram.product.created',
                model: $product,
                new: $product->toArray(),
                tags: ['telegram', 'product', 'create']
            );

            $this->sendToTelegram($product, $product->image);

            return response()->json([
                'message' => 'Produto cadastrado e enviado',
                'product_id' => $product->id,
            ], 201);

        } 

        catch (\Throwable $e) {
                Log::error('❌ Erro TelegramWebhookController', [
                    'error' => $e->getMessage(),
                ]);
            
                return null;
            }

    }


    /* =====================================================
     * MÉTODOS AUXILIARES
     * ===================================================== */

    private function getTelegramFileUrl(string $fileId): ?string
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $json = file_get_contents("https://api.telegram.org/bot{$token}/getFile?file_id={$fileId}");
        $data = json_decode($json, true);
        return isset($data['result']['file_path'])
            ? "https://api.telegram.org/file/bot{$token}/{$data['result']['file_path']}"
            : null;
    }

    private function extractPrice(string $text, string $type): ?string
    {
        if ($type === 'promo' && preg_match('/Por\s+R\$\s*([\d.,]+)/i', $text, $m)) {
            return $m[1];
        }
        if ($type === 'original' && preg_match('/De\s+R\$\s*([\d.,]+)/i', $text, $m)) {
            return $m[1];
        }
        return null;
    }

    private function sendToTelegram(Product $product, $photoPath = null): bool
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
    
        if (!$token || !$chatId) {
            Log::warning('⚠️ Telegram não configurado.');
            return false;
        }
    
        /*
        |--------------------------------------------------------------------------
        | 🔗 LINK (COMPLIANCE AFILIADOS)
        |--------------------------------------------------------------------------
        */
        $affiliateLink = strtolower($product->link);
    
        $isAmazon           = str_contains($affiliateLink, 'amzn.');
        $isMercadoLivre     = str_contains($affiliateLink, 'meli.');
        $isMagalu           = str_contains($affiliateLink, 'magalu.');
        $isShopee           = str_contains($affiliateLink, 'shopee.');
    
        $link = ($isAmazon || $isMercadoLivre || $isMagalu || $isShopee) ? url('/product/' . $product->slug) : $product->link;
    
        /*
        |--------------------------------------------------------------------------
        | 💰 PREÇOS
        |--------------------------------------------------------------------------
        */
        $originalPrice = $product->original_price
            ? number_format($product->original_price, 2, ',', '.')
            : null;
    
        $promoPrice = $product->promo_price
            ? number_format($product->promo_price, 2, ',', '.')
            : null;
    
        $priceBlock = '';
        if ($originalPrice && $promoPrice && $originalPrice !== $promoPrice) {
            $priceBlock =
                "💰 De <s>~R$ {$originalPrice}~</s>\n" .
                "💸 <b>Por R$ {$promoPrice}</b> 🚀🔥\n\n";
            } elseif ($promoPrice) {
            $priceBlock = "💸 <b>Por R$ {$promoPrice}</b> 🚀🔥\n\n";
        }
    
        /*
        |--------------------------------------------------------------------------
        | 📝 MENSAGEM
        |--------------------------------------------------------------------------
        */
        $companyName = $product->company?->name ?? '';
        
        $caption =
        "🛍 {$product->title}\n\n" .
        $priceBlock .
        "🛒 Ver oferta da {$companyName} 👇\n" .
        "{$link}\n\n" .
        "⚠️ Promoção sujeita a alteração de preço e estoque.";

        /*
        |--------------------------------------------------------------------------
        | 🖼 IMAGEM (MODO QUE FUNCIONA)
        |--------------------------------------------------------------------------
        */
        $filePath = $photoPath && !filter_var($photoPath, FILTER_VALIDATE_URL) ? base_path($photoPath) : null;
    
        if (!$filePath || !file_exists($filePath)) {
            Log::warning('⚠️ Imagem não encontrada para envio Telegram', [
                'product_id' => $product->id,
                'path' => $filePath
            ]);
            return false;
        }
    
        /*
        |--------------------------------------------------------------------------
        | 📤 ENVIO TELEGRAM
        |--------------------------------------------------------------------------
        */
        $ch = curl_init("https://api.telegram.org/bot{$token}/sendPhoto");
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_POSTFIELDS => [
                'chat_id'    => $chatId,
                'photo'      => new \CURLFile($filePath),
                'caption'    => $caption,
                'parse_mode' => 'HTML',
            ],
        ]);
    
        curl_exec($ch);
        curl_close($ch);
    
        Log::info('✅ Produto enviado ao Telegram', [
            'product_id'  => $product->id,
            'link_enviado'=> $link
        ]);
    
        return true;
    }

    private function downloadImage(string $url): ?string
    {
        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $name = uniqid() . '.' . $ext;
        $dir = base_path('uploads/imgProducts/');
        if (!file_exists($dir)) mkdir($dir, 0755, true);
        file_put_contents($dir . $name, file_get_contents($url));
        return 'uploads/imgProducts/' . $name;
    }

    private function identifyCompanyFromLink(string $text): ?Company
    {
        $text = Str::lower($text);
        foreach (KeywordCompany::with('company')->get() as $keyword) {
            if ($keyword->company && str_contains($text, Str::lower($keyword->name))) {
                return $keyword->company;
            }
        }
        return null;
    }

    private function identifyCategoryFromText_R00(string $title, ?string $description = null): ?Category
    {
        $text = mb_strtolower($title . ' ' . ($description ?? ''));
        foreach (KeywordProduct::with('category')->get() as $keyword) {
            if ($keyword->category && str_contains($text, mb_strtolower($keyword->name))) {
                return $keyword->category;
            }
        }
        return Category::where('name', 'like', '%Outros%')->first();
    }
    
    private function identifyCategoryFromText(string $title, ?string $description = null): Category
    {
         
        Log::info('✅ Estou na função de indentificar a palavra chave da categoria !');
        
        $text = mb_strtolower($title . ' ' . ($description ?? ''), 'UTF-8');
    
        $keywords = KeywordProduct::with('category')->get();
    
        foreach ($keywords as $keyword) {
    
            if (!$keyword->category) {
                continue;
            }
    
            // 🔒 Palavra exata (evita "pet" em "potes")
            if (preg_match('/\b' . preg_quote(mb_strtolower($keyword->name), '/') . '\b/iu', $text)) {
    
                Log::info('🧠 Categoria detectada automaticamente', [
                    'categoria' => $keyword->category->name,
                    'keyword'   => $keyword->name,
                    'titulo'    => $title,
                ]);
    
                return $keyword->category;
            }
        }
    
        // 🔁 Fallback correto → Outros (ID 11)
        Log::info('⚙️ Categoria padrão aplicada: Outros', [
            'titulo' => $title
        ]);
    
        return Category::find(11);
    }

    private function validateMarketplaceFromDatabase(string $link): ?Company
    {
        $host = parse_url($link, PHP_URL_HOST);
    
        if (!$host) {
            Log::warning('❌ Link inválido (host não encontrado)', [
                'link' => $link
            ]);
            return null;
        }
    
        $host = str_replace('www.', '', strtolower($host));
    
        Log::info('🔍 Validando marketplace via BD', [
            'host' => $host
        ]);
    
        $keywords = KeywordCompany::with('company')->get();
    
        foreach ($keywords as $keyword) {
    
            if (!$keyword->company) {
                continue;
            }
    
            if (str_contains($host, strtolower($keyword->name))) {
    
                Log::info('✅ Marketplace autorizado (BD)', [
                    'empresa' => $keyword->company->name,
                    'keyword' => $keyword->name,
                    'host'    => $host,
                ]);
    
                return $keyword->company;
            }
        }
    
        Log::warning('⛔ Marketplace NÃO autorizado (BD)', [
            'host' => $host,
            'link' => $link
        ]);
    
        return null;
    }
}
