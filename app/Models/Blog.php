<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// 🔹 Auditoria centralizada
use App\Traits\AuditTrait;

class Blog extends Model
{
    use HasFactory, AuditTrait;

    // ================== CONFIGURAÇÕES ==================

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'published',
        'published_at',
    ];

    protected $casts = [
        'published'    => 'boolean',
        'published_at' => 'datetime',
    ];

    // ================== ROUTE KEY ==================

    /**
     * 🔑 Usar slug na URL
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ================== BOOT ==================

    protected static function booted()
    {
        parent::booted();

        // 🆕 POST CRIADO
        static::created(function ($blog) {
            static::auditCreated($blog, ['blog', 'content']);
        });

        // ✏️ POST ATUALIZADO
        static::updated(function ($blog) {
            if (!empty($blog->getChanges())) {
                static::auditUpdated($blog, ['blog', 'content']);
            }
        });

        // 🗑️ POST EXCLUÍDO
        static::deleted(function ($blog) {
            static::auditDeleted($blog, ['blog', 'content']);
        });

        // 🔗 SLUG AUTOMÁTICO
        static::saving(function ($blog) {

            if ($blog->isDirty('title') || empty($blog->slug)) {

                $baseSlug = Str::slug($blog->title);
                $slug     = $baseSlug;
                $count    = 1;

                while (
                    self::where('slug', $slug)
                        ->where('id', '!=', $blog->id)
                        ->exists()
                ) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $blog->slug = $slug;
            }
        });
    }
}
