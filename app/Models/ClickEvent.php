<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Relacionamentos
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Company;

// Audit
use App\Traits\AuditTrait;

class ClickEvent extends Model
{
    use HasFactory, AuditTrait;

    protected $table = 'click_events';

    protected $fillable = [
        'action',
        'product_id',
        'coupon_id',
        'company_id',
        'source',        // telegram | web | app
        'ip',
        'user_agent',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT (AUDITORIA CONTROLADA)
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        parent::booted();

        /**
         * 🔴 IMPORTANTE:
         * ClickEvent NÃO audita automaticamente.
         * Auditoria só deve acontecer quando você CHAMAR explicitamente.
         */

        // Exemplo opcional (se quiser auditar exclusão manual)
        static::deleted(function ($clickEvent) {
            self::audit(
                event: 'delete',
                model: $clickEvent,
                old: [
                    'action'     => $clickEvent->action,
                    'product_id' => $clickEvent->product_id,
                    'source'     => $clickEvent->source,
                ],
                new: [],
                tags: ['click-event']
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE MÉTRICA (SEM AUDITORIA)
    |--------------------------------------------------------------------------
    */

    /**
     * Remove eventos antigos (default: 30 dias)
     */
    public static function deleteOld(int $days = 30): void
    {
        self::where('created_at', '<', now()->subDays($days))->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | AUDITORIA MANUAL (OPCIONAL)
    |--------------------------------------------------------------------------
    */

    /**
     * Auditoria de acesso humano ao relatório de cliques
     */
    public function auditAccess(): void
    {
        self::audit(
            event: 'access',
            model: $this,
            old: [],
            new: [
                'action' => $this->action,
                'source' => $this->source,
            ],
            tags: ['click-event', 'access']
        );
    }
}
