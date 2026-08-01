<?php

namespace App\Http\Middleware;

use App\Services\ApiTokenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiConnection
{
    /**
     * Valida que exista un token API activo para usuarios autenticados.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $tokenService = app(ApiTokenService::class);
        $token = $tokenService->getValidToken($user);

        if ($request->hasSession()) {
            $request->session()->put('api_connection_active', !empty($token));
            $request->session()->put('api_connection_last_check', now()->toDateTimeString());
        }

        if (empty($token)) {
            Log::warning("No se pudo mantener conexión API para usuario {$user->email}");
        }

        return $next($request);
    }
}
