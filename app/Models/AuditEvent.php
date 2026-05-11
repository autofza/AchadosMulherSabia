<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class AuditEvent extends Model
{
    protected $table = 'audits';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'tags'       => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    public function scopeAuditableType($query, string $type)
    {
        return $query->where('auditable_type', $type);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔐 LOG MANUAL DE AUDITORIA (SEGURO)
    |--------------------------------------------------------------------------
    */

    public static function log(
        string $event,
        ?Model $model = null,
        array $old = [],
        array $new = [],
        array $tags = []
    ): void {
        $user = auth()->user();

        self::create([
            'user_type'      => $user ? get_class($user) : null,
            'user_id'        => $user?->id,

            // Evento (login, logout, login_failed, etc.)
            'event'          => $event,

            // 🔥 NUNCA NULL
            'auditable_type' => $model
                ? get_class($model)
                : ($user ? get_class($user) : self::class),

            'auditable_id'   => $model?->id ?? 0,

            'old_values'     => empty($old) ? null : $old,
            'new_values'     => empty($new) ? null : $new,

            'url'            => Request::fullUrl(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),

            'tags'           => empty($tags) ? null : $tags,
        ]);
    }
}
