<?php

namespace App\Traits;

use App\Models\AuditEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait AuditTrait
{
    /**
     * Registra um evento de auditoria
     *
     * @param string $event
     * @param Model  $model
     * @param array  $old
     * @param array  $new
     * @param array  $tags
     * @return void
     */
    protected static function audit(
        string $event,
        Model $model,
        array $old = [],
        array $new = [],
        array $tags = []
    ): void {
        try {
            AuditEvent::create([
                'user_type'      => Auth::check() ? get_class(Auth::user()) : null,
                'user_id'        => Auth::id(),
                'event'          => $event,
                'auditable_type' => get_class($model),
                'auditable_id'   => $model->getKey(),
                'old_values'     => !empty($old) ? $old : null,
                'new_values'     => !empty($new) ? $new : null,
                'url'            => request()?->fullUrl(),
                'ip_address'     => request()?->ip(),
                'user_agent'     => request()?->userAgent(),
                'tags'           => !empty($tags) ? implode(',', $tags) : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Erro ao gravar audit_event', [
                'model' => get_class($model),
                'event' => $event,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS AUXILIARES (OPCIONAL, MAS ÚTIL)
    |--------------------------------------------------------------------------
    */

    protected static function auditCreated(Model $model, array $tags = []): void
    {
        static::audit(
            'created',
            $model,
            [],
            $model->getAttributes(),
            $tags
        );
    }

    protected static function auditUpdated(Model $model, array $tags = []): void
    {
        static::audit(
            'updated',
            $model,
            $model->getOriginal(),
            $model->getChanges(),
            $tags
        );
    }

    protected static function auditDeleted(Model $model, array $tags = []): void
    {
        static::audit(
            'deleted',
            $model,
            $model->getAttributes(),
            [],
            $tags
        );
    }
}
