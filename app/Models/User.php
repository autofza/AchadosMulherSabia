<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

// Auditoria
use App\Traits\AuditTrait;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, AuditTrait;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT (AUDITORIA)
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        parent::booted();

        // 🆕 Usuário criado
        static::created(function ($user) {
            self::audit(
                event: 'create',
                model: $user,
                old: [],
                new: $user->getAttributes(),
                tags: ['user']
            );
        });

        // ✏️ Usuário atualizado
        static::updated(function ($user) {
            if (!empty($user->getChanges())) {
                self::audit(
                    event: 'update',
                    model: $user,
                    old: $user->getOriginal(),
                    new: $user->getChanges(),
                    tags: ['user']
                );
            }
        });

        // 🗑️ Usuário removido
        static::deleted(function ($user) {
            self::audit(
                event: 'delete',
                model: $user,
                old: $user->getOriginal(),
                new: [],
                tags: ['user']
            );
        });
    }
}
