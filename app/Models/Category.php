<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// 🔹 Auditoria centralizada
use App\Traits\AuditTrait;

// Relacionamentos
use App\Models\Product;

class Category extends Model
{
    use HasFactory, AuditTrait;

    // ================== CONFIGURAÇÕES ==================

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // ================== ROUTE KEY ==================

    /**
     * 🔑 Sempre usar o slug na rota (SEO)
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ================== BOOT ==================

    protected static function booted()
    {
        parent::booted();

        // 🆕 CATEGORIA CRIADA
        static::created(function ($category) {
            static::auditCreated($category, ['category']);
        });

        // ✏️ CATEGORIA ATUALIZADA
        static::updated(function ($category) {
            if (!empty($category->getChanges())) {
                static::auditUpdated($category, ['category']);
            }
        });

        // 🗑️ CATEGORIA EXCLUÍDA
        static::deleted(function ($category) {
            static::auditDeleted($category, ['category']);
        });

        // 🔗 SLUG AUTOMÁTICO
        static::saving(function ($category) {

            if ($category->isDirty('name') || empty($category->slug)) {

                $baseSlug = Str::slug($category->name);
                $slug     = $baseSlug;
                $count    = 1;

                while (
                    self::where('slug', $slug)
                        ->where('id', '!=', $category->id)
                        ->exists()
                ) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $category->slug = $slug;
            }
        });
    }

    // ================== RELACIONAMENTOS ==================

    /**
     * ✅ Produtos ATIVOS (uso público / site)
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')
            ->active();
    }

    /**
     * 🔓 Produtos SEM filtro (uso ADMIN)
     */
    public function productsAll()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
