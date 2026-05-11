<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// 🔹 Auditoria centralizada
use App\Traits\AuditTrait;

class KeywordCompany extends Model
{
    use HasFactory, AuditTrait;

    // ================== CONFIGURAÇÕES ==================

    protected $table = 'keyword_companies';

    protected $fillable = [
        'name',
        'slug',
        'company_id',
    ];

    // ================== ROUTE KEY ==================

    /**
     * Define que a busca por rota usará o slug
     * Ex: /keywords/amazon-prime
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ================== RELACIONAMENTOS ==================

    /**
     * Palavra-chave pertence a uma Empresa
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // ================== BOOT ==================

    protected static function booted()
    {
        parent::booted();

        // 🆕 CRIADO
        static::created(function ($keywordCompany) {
            static::auditCreated($keywordCompany, ['keyword', 'company']);
        });

        // ✏️ ATUALIZADO
        static::updated(function ($keywordCompany) {
            if (!empty($keywordCompany->getChanges())) {
                static::auditUpdated($keywordCompany, ['keyword', 'company']);
            }
        });

        // 🗑️ EXCLUÍDO
        static::deleted(function ($keywordCompany) {
            static::auditDeleted($keywordCompany, ['keyword', 'company']);
        });

        // 🔗 SLUG AUTOMÁTICO
        static::saving(function ($keywordCompany) {

            if ($keywordCompany->isDirty('name') || empty($keywordCompany->slug)) {

                $baseSlug = Str::slug($keywordCompany->name);
                $slug     = $baseSlug;
                $count    = 1;

                while (
                    self::where('slug', $slug)
                        ->where('id', '!=', $keywordCompany->id)
                        ->exists()
                ) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }

                $keywordCompany->slug = $slug;
            }
        });
    }
}
