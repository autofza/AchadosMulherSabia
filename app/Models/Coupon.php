<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Relacionamentos
use App\Models\Company;

// Auditoria
use App\Traits\AuditTrait;

class Coupon extends Model
{
    use HasFactory, AuditTrait;

    protected $fillable = [
        'code',
        'value',
        'company_id',
        'active',
        'link',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    /**
     * 🏢 Cupom pertence a uma empresa
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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
        static::created(function ($coupon) {
            self::audit(
                event: 'create',
                model: $coupon,
                old: [],
                new: $coupon->getAttributes(),
                tags: ['coupon']
            );
        });

        // ✏️ ATUALIZADO
        static::updated(function ($coupon) {
            if (!empty($coupon->getChanges())) {
                self::audit(
                    event: 'update',
                    model: $coupon,
                    old: $coupon->getOriginal(),
                    new: $coupon->getChanges(),
                    tags: ['coupon']
                );
            }
        });

        // 🗑️ DELETADO
        static::deleted(function ($coupon) {
            self::audit(
                event: 'delete',
                model: $coupon,
                old: $coupon->getOriginal(),
                new: [],
                tags: ['coupon']
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MANUTENÇÃO
    |--------------------------------------------------------------------------
    */

    /**
     * 🧹 Apaga cupons com mais de 7 dias
     */
    public static function deleteOld(): void
    {
        self::where('created_at', '<', now()->subDays(7))->delete();
    }
}
