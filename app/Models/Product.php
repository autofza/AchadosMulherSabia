<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

// 🔹 Auditoria
use App\Traits\AuditTrait;

// Relacionamentos
use App\Models\Coupon;
use App\Models\Company;
use App\Models\Category;

class Product extends Model
{
    use HasFactory, AuditTrait;

    // ================== CONFIGURAÇÕES ==================

    public const ACTIVE_DAYS = 2;
    public const DELETE_DAYS = 10;
    public const COUNT_DAYS  = 10;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'link',
        'category_id',
        'original_price',
        'promo_price',
        'description',
        'active',
        'company_id',
        'coupon_id',
        'inspired',
    ];

    protected $casts = [
        'inspired'        => 'datetime',
        'active'          => 'boolean',
        'original_price'  => 'float',
        'promo_price'     => 'float',
    ];

    protected $attributes = [
        'image' => 'uploads/imgSem.jpg',
    ];

    // ================== RELACIONAMENTOS ==================

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // ================== ACCESSORS ==================

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('uploads/imgSem.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return asset($this->image);
    }

    // ================== SCOPES ==================

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // ================== BOOT ==================

    protected static function booted()
    {
        parent::booted();

        // 🆕 PRODUTO CRIADO (AUDIT)
        static::created(function ($product) {
            static::auditCreated($product, ['product']);
        });

        // ✏️ PRODUTO ATUALIZADO (AUDIT)
        static::updated(function ($product) {
            if (!empty($product->getChanges())) {
                static::auditUpdated($product, ['product']);
            }
        });

        // 🗑️ PRODUTO EXCLUÍDO (AUDIT)
        static::deleted(function ($product) {
            static::auditDeleted($product, ['product']);
        });

        // 🔗 SLUG AUTOMÁTICO (LIMITE 50 + ÚNICO)
        static::saving(function ($product) {

            $baseSlug = Str::slug($product->title);
            $baseSlug = Str::limit($baseSlug, 50, '');

            $slug  = $baseSlug;
            $count = 1;

            while (
                self::where('slug', $slug)
                    ->where('id', '!=', $product->id)
                    ->exists()
            ) {
                $suffix = '-' . $count;
                $slug = Str::limit($baseSlug, 50 - strlen($suffix), '') . $suffix;
                $count++;
            }

            $product->slug = $slug;
        });

        // 🧹 REMOVE IMAGEM AO DELETAR
        static::deleting(function ($product) {

            if (
                empty($product->image) ||
                $product->image === 'uploads/imgSem.jpg' ||
                filter_var($product->image, FILTER_VALIDATE_URL)
            ) {
                return;
            }

            $imagePath = public_path($product->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
                Log::info('🧹 Imagem do produto excluída', [
                    'product_id' => $product->id,
                    'path'       => $imagePath
                ]);
            } else {
                Log::warning('❌ Imagem não encontrada para exclusão', [
                    'product_id' => $product->id,
                    'path'       => $imagePath
                ]);
            }
        });

        // ⚙️ CONTROLE AUTOMÁTICO DE STATUS
        if (!app()->runningInConsole()) {

            static::checkAndUpdateStatus();

            if (request()->is('/') || request()->is('site/*')) {
                static::addGlobalScope('active_only', function ($query) {
                    $query->where('active', true);
                });
            }
        }
    }

    // ================== STATUS AUTOMÁTICO ==================

    protected static function checkAndUpdateStatus_R00()
    {
        try {
            $deactivated = self::withoutGlobalScopes()
                ->whereNotNull('inspired')
                ->where('inspired', '<', now())
                ->where('active', 1)
                ->update(['active' => 0]);

            if ($deactivated > 0) {
                Log::info('⏳ Produtos expirados desativados', ['qtd' => $deactivated]);
            }

        } catch (\Throwable $e) {
            Log::error('Erro no controle automático de status', [
                'erro' => $e->getMessage()
            ]);
        }
    }

    protected static function checkAndUpdateStatus()
    {
        try {
    
            $expiredProducts = self::withoutGlobalScopes()
                ->whereNotNull('inspired')
                ->where('inspired', '<', now())
                ->where('active', 1)
                ->get();
    
            foreach ($expiredProducts as $product) {
    
                $old = ['active' => true];
    
                $product->update(['active' => false]);
    
                // 🧾 AUDIT EVENT
                static::audit(
                    event: 'product.expired',
                    model: $product,
                    old: $old,
                    new: ['active' => false],
                    tags: ['product', 'auto', 'expired']
                );
            }
    
            $deactivated = $expiredProducts->count();
    
            if ($deactivated > 0) {
                Log::info('⏳ Produtos expirados desativados', [
                    'qtd' => $deactivated
                ]);
            }
    
        } catch (\Throwable $e) {
            Log::error('Erro no controle automático de status', [
                'erro' => $e->getMessage()
            ]);
        }
    }

    // ================== LIMPEZA ==================

    public static function deleteOld_R00(): int
    {
        $total = self::withoutGlobalScopes()
            ->where('active', 0)
            ->whereNotNull('inspired')
            ->where('inspired', '<', now()->subDays(self::DELETE_DAYS))
            ->delete();

        if ($total > 0) {
            Log::info('🧽 Limpeza de produtos antigos', ['qtd' => $total]);
        }

        return $total;
    }
    
    public static function deleteOld(): int
    {
        $products = self::withoutGlobalScopes()
            ->where('active', 0)
            ->whereNotNull('inspired')
            ->where('inspired', '<', now()->subDays(self::DELETE_DAYS))
            ->get();
    
        foreach ($products as $product) {
    
            // 🧾 AUDIT EVENT ANTES DO DELETE
            static::audit(
                event: 'product.auto_deleted',
                model: $product,
                old: $product->toArray(),
                tags: ['product', 'auto', 'cleanup']
            );
    
            $product->delete();
        }
    
        $total = $products->count();
    
        if ($total > 0) {
            Log::info('🧽 Limpeza de produtos antigos', [
                'qtd' => $total
            ]);
        }
    
        return $total;
    }

    public static function countOld(): int
    {
        return self::withoutGlobalScopes()
            ->where('active', 0)
            ->whereNotNull('inspired')
            ->where('inspired', '<', now()->subDays(self::COUNT_DAYS))
            ->count();
    }
}
