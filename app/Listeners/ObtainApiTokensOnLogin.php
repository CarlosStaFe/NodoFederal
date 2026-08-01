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
            // Garantizar conexión API activa al inicio de sesión
            $success = !empty($this->apiTokenService->getValidToken($user));

            if (request()->hasSession()) {
                request()->session()->put('api_connection_active', $success);
                request()->session()->put('api_connection_started_at', now()->toDateTimeString());
            }
            
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