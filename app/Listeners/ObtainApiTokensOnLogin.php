<?php

namespace App\Listeners;

use App\Services\ApiTokenService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class ObtainApiTokensOnLogin
{
    protected $apiTokenService;

    /**
     * Create the event listener.
     */
    public function __construct(ApiTokenService $apiTokenService)
    {
        $this->apiTokenService = $apiTokenService;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        Log::info("Usuario autenticado: {$user->email}, obteniendo tokens API en memoria...");
        
        try {
            // Obtener tokens automáticamente al login y almacenar solo en cache
            $success = $this->apiTokenService->obtainTokensOnLogin($user);
            
            if ($success) {
                Log::info("Tokens API obtenidos y almacenados en memoria para {$user->email}");
            } else {
                Log::warning("No se pudieron obtener tokens API para {$user->email}");
            }
            
        } catch (\Exception $e) {
            Log::error("Error obteniendo tokens en login para {$user->email}: " . $e->getMessage());
        }
    }
}