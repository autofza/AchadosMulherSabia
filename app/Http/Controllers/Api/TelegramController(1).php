<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use CURLFile;

class TelegramController
{
    public function sendProduct(Product $product, ?string $photoPath = null): bool
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            Log::warning('Telegram não configurado.');
            return false;
        }

        if (
            empty($photoPath) ||
            $photoPath === 'uploads/imgSem.jpg'
        ) {
            Log::warning('Imagem inválida para envio Telegram', [
                'product_id' => $product->id
            ]);
            return false;
        }

        $link = $this->getAffiliateLink($product);

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
                "💸 <b>Por Apenas R$ {$promoPrice}</b> 🚀🔥\n\n";
        } elseif ($promoPrice) {
            $priceBlock =
                "💸 <b>Por Apenas R$ {$promoPrice}</b> 🚀🔥\n\n";
        }

        $caption =
            "🛍 <b>{$product->title}</b>\n\n" .
            $priceBlock .
            "🛒 <b>Compre aqui:</b> 👉 <a href=\"{$link}\">{$link}</a>\n\n" .
            "⚠️ <i>Promoção sujeita a alteração a qualquer momento.</i>";

        $filePath = public_path($photoPath);

        if (!file_exists($filePath)) {
            Log::warning('Imagem não encontrada para envio ao Telegram', [
                'product_id' => $product->id,
                'file' => $filePath
            ]);
            return false;
        }

        $url = "https://api.telegram.org/bot{$token}/sendPhoto";

        $postFields = [
            'chat_id' => $chatId,
            'photo' => new CURLFile($filePath),
            'caption' => $caption,
            'parse_mode' => 'HTML',
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        if (!$response || empty($response['ok'])) {
            Log::error('Erro API Telegram', $response ?? []);
            return false;
        }

        Log::info('Produto enviado ao Telegram', [
            'product_id' => $product->id
        ]);

        return true;
    }

    private function getAffiliateLink(Product $product): string
    {
        $originalLink = trim(str_replace('c1om', 'com', $product->link));

        if (!filter_var($originalLink, FILTER_VALIDATE_URL)) {
            return url("/product/{$product->id}");
        }

        $host = strtolower(parse_url($originalLink, PHP_URL_HOST) ?? '');

        if (
            str_contains($host, 'amzn.to') ||
            str_contains($host, 'mercadolivre') ||
            str_contains($host, '.ml')
        ) {
            return url("/product/{$product->id}");
        }

        return $originalLink;
    }
}
