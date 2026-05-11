<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

// Relacionamentos
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;

// Auditoria
use App\Traits\AuditTrait;

class Company extends Model
{
    use AuditTrait;

    protected $fillable = [
        'name',
        'slug',
        'soon', // logo / flag visual
        'link',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    /**
     * 🛍️ Uma empresa possui vários produtos
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'company_id');
    }

    /**
     * 🎟️ Uma empresa possui vários cupons
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'company_id');
    }

    /**
     * 🗂️ Categorias vinculadas via produtos ATIVOS (público)
     */
    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(
            Category::class,
            Product::class,
            'company_id',
            'id',
            'id',
            'category_id'
        )
            ->where('products.active', 1)
            ->distinct()
            ->orderBy('categories.name');
    }

    /**
     * 🗂️ Categorias vinculadas via produtos (admin)
     */
    public function categoriesAll(): HasManyThrough
    {
        return $this->hasManyThrough(
            Category::class,
            Product::class,
            'company_id',
            'id',
            'id',
            'category_id'
        )
            ->distinct()
            ->orderBy('categories.name');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * 🔐 URL criptografada para redirecionamento
     */
    public function getEncryptedUrlAttribute(): string
    {
        return url('/r/' . Crypt::encryptString("company:{$this->id}"));
    }

    /**
     * 🔑 Usa slug nas rotas
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        parent::booted();

        // 🆕 CRIADO
        static::created(function ($company) {
            self::audit(
                event: 'create',
                model: $company,
                old: [],
                new: $company->getAttributes(),
                tags: ['company']
            );
        });

        // ✏️ ATUALIZADO
        static::updated(function ($company) {
            if (!empty($company->getChanges())) {
                self::audit(
                    event: 'update',
                    model: $company,
                    old: $company->getOriginal(),
                    new: $company->getChanges(),
                    tags: ['company']
                );
            }
        });

        // 🗑️ DELETADO
        static::deleted(function ($company) {
            self::audit(
                event: 'delete',
                model: $company,
                old: $company->getOriginal(),
                new: [],
                tags: ['company']
            );
        });

        // 📝 SLUG AUTOMÁTICO
        static::saving(function ($company) {
            if (!$company->slug || $company->isDirty('name')) {
                $company->slug = Str::slug($company->name);
            }
        });
    }
}
