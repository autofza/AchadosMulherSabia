<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// 🔹 Auditoria centralizada
use App\Traits\AuditTrait;

class KeywordProduct extends Model
{
    use HasFactory, AuditTrait;

    // ================== CONFIGURAÇÕES ==================

    protected $table = 'keyword_products';

    protected $fillable = [
        'name',
        'slug',
        'category_id',
    ];

    // ================== ROUTE KEY ==================

    /**
     * Sempre usar slug na rota (ex: site.com/keyword/tenis-nike)
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ================== RELACIONAMENTOS ==================

    /**
     * Palavra-chave pertence a uma Categoria
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // ================== BOOT ==================

    protected static function booted()
    {
        parent::booted();

        // 🆕 CRIADO
        static::created(function ($keyword) {
            static::auditCreated($keyword, ['keyword', 'product']);
        });

        // ✏️ ATUALIZADO
        static::updated(function ($keyword) {
            if (!empty($keyword->getChanges())) {
                static::auditUpdated($keyword, ['keyword', 'product']);
            }
        });

        // 🗑️ EXCLUÍDO
        static::deleted(function ($keyword) {
            static::auditDeleted($keyword, ['keyword', 'product']);
        });

        // 🔗 SLUG AUTOMÁTICO
        static::saving(function ($keyword) {

            if ($keyword->isDirty('name') || empty($keyword->slug)) {

                $baseSlug = Str::slug($keyword->name);
                $slug     = $baseSlug;
                $count    = 1;

                while (
                    self::where('slug', $slug)
                        ->where('id', '!=', $keyword->id)
                        ->exists()
                ) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $keyword->slug = $slug;
            }
        });
    }
}
