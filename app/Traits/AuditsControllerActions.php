<?php

namespace App\Traits;

use App\Models\AuditEvent;
use Illuminate\Database\Eloquent\Model;

trait AuditsControllerActions
{
    /**
     * 🔐 Auditoria padrão de ações em controllers
     *
     * @param string $event   Ex: product.created, product.updated
     * @param Model|null $model
     * @param array $old
     * @param array $new
     * @param array $tags
     */
    protected function audit(
        string $event,
        ?Model $model = null,
        array $old = [],
        array $new = [],
        array $tags = []
    ): void {
        AuditEvent::log(
            event: $event,
            model: $model,
            old: $old ?: [],
            new: $new ?: [],
            tags: $tags ?: []
        );
    }

    /**
     * 🔎 Auditoria de visualização (read-only)
     */
    protected function auditView(string $event, ?Model $model = null): void
    {
        AuditEvent::log(
            event: $event,
            model: $model,
            tags: ['view']
        );
    }

    /**
     * ⚠️ Auditoria de erro
     */
    protected function auditError(
        string $event,
        string $message,
        ?Model $model = null
    ): void {
        AuditEvent::log(
            event: $event,
            model: $model,
            new: ['error' => $message],
            tags: ['error']
        );
    }
}
