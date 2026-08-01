<?php

namespace App\Listeners;

use App\Services\ApiTokenService;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class InvalidateTokensOnLogout
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
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if (request()->hasSession()) {
            request()->session()->forget([
                'api_connection_active',
                'api_connection_started_at',
                'api_connection_last_check',
            ]);
        }
        
        if ($user) {
            Log::info("Usuario cerrando sesión: {$user->email}, invalidando tokens...");
            
            try {
                // Invalidar tokens del cache por seguridad
                $this->apiTokenService->invalidateUserToken($user);
                Log::info("Tokens invalidados para {$user->email}");
                
            } catch (\Exception $e) {
                Log::error("Error invalidando tokens en logout para {$user->email}: " . $e->getMessage());
            }
        }
    }
}