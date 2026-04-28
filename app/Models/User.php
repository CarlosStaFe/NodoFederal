<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable, AuditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nodo_id',
        'socio_id',
        'created_by',
        'updated_by',
    ];

    public function nodo()
    {
        return $this->belongsTo(Nodo::class);
    }

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Obtiene un token válido para este usuario desde cache/memoria
     */
    public function getApiToken(): ?string
    {
        $tokenService = app(\App\Services\ApiTokenService::class);
        return $tokenService->getValidToken($this);
    }

    /**
     * Obtiene información del token del usuario
     */
    public function getTokenInfo(): ?array
    {
        $tokenService = app(\App\Services\ApiTokenService::class);
        return $tokenService->getTokenInfo($this);
    }

    /**
     * Verifica si el usuario tiene un token válido en memoria
     */
    public function hasValidApiToken(): bool
    {
        $tokenInfo = $this->getTokenInfo();
        return $tokenInfo && $tokenInfo['is_valid'];
    }

    /**
     * Invalida el token del usuario en memoria
     */
    public function invalidateApiToken(): void
    {
        $tokenService = app(\App\Services\ApiTokenService::class);
        $tokenService->invalidateUserToken($this);
    }

    /**
     * Fuerza la renovación del token del usuario
     */
    public function refreshApiToken(): bool
    {
        $tokenService = app(\App\Services\ApiTokenService::class);
        return $tokenService->forceRefreshUserToken($this);
    }
}
