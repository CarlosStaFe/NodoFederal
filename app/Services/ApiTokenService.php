<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiTokenService
{
    private const CACHE_PREFIX = 'user_token_';
    private const GLOBAL_TOKEN_KEY = 'global_api_token';
    private const DEFAULT_TOKEN_TTL = 3600; // 1 hora por defecto

    /**
     * Obtiene un token válido para el usuario desde cache o API
     */
    public function getValidToken(?User $user = null): ?string
    {
        // Si no hay usuario, usar token global
        if (!$user) {
            return $this->getGlobalToken();
        }

        $cacheKey = self::CACHE_PREFIX . $user->id;
        
        // Verificar si hay token válido en cache
        $tokenData = Cache::get($cacheKey);
        if ($tokenData && $this->isTokenValid($tokenData)) {
            
            // Verificar si está cerca de expirar (menos de 10 minutos) para renovar proactivamente
            if ($this->isTokenNearExpiry($tokenData)) {
                Log::info("Token cerca del vencimiento, renovando proactivamente para usuario {$user->id}");
                
                if (isset($tokenData['refresh_token'])) {
                    $newTokenData = $this->refreshToken($tokenData['refresh_token']);
                    if ($newTokenData) {
                        $this->cacheTokenForUser($user, $newTokenData);
                        Log::info("Token renovado proactivamente para usuario {$user->id}");
                        return $newTokenData['access_token'];
                    }
                }
            }
            
            Log::info("Token obtenido desde cache para usuario {$user->id}");
            return $tokenData['access_token'];
        }

        // Token expirado o no existe, intentar renovar si hay refresh_token
        if ($tokenData && isset($tokenData['refresh_token'])) {
            $newTokenData = $this->refreshToken($tokenData['refresh_token']);
            if ($newTokenData) {
                $this->cacheTokenForUser($user, $newTokenData);
                Log::info("Token renovado exitosamente para usuario {$user->id}");
                return $newTokenData['access_token'];
            }
        }

        // Obtener token completamente nuevo
        $newTokenData = $this->obtainNewToken();
        if ($newTokenData) {
            $this->cacheTokenForUser($user, $newTokenData);
            Log::info("Nuevo token obtenido para usuario {$user->id}");
            return $newTokenData['access_token'];
        }

        Log::error("No se pudo obtener token válido para usuario {$user->id}");
        return null;
    }

    /**
     * Obtiene tokens automáticamente al login del usuario
     */
    public function obtainTokensOnLogin(User $user): bool
    {
        try {
            Log::info("Obteniendo tokens para login de usuario {$user->email}");
            
            $tokenData = $this->obtainNewToken();
            if (!$tokenData) {
                Log::warning("No se pudieron obtener tokens en login para usuario {$user->email}");
                return false;
            }

            // Guardar tokens solo en cache/memoria
            $this->cacheTokenForUser($user, $tokenData);
            
            Log::info("Tokens obtenidos y almacenados en memoria para usuario {$user->email}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Error obteniendo tokens en login: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un token es válido (no expirado)
     */
    private function isTokenValid(array $tokenData): bool
    {
        if (!isset($tokenData['expires_at'])) {
            return false;
        }

        $expiresAt = Carbon::parse($tokenData['expires_at']);
        return $expiresAt->isFuture();
    }

    /**
     * Verifica si un token está cerca de expirar (menos de 10 minutos)
     * para renovarlo proactivamente usando refresh_token
     */
    private function isTokenNearExpiry(array $tokenData): bool
    {
        if (!isset($tokenData['expires_at'])) {
            return true; // Si no hay fecha de expiración, mejor renovar
        }

        $expiresAt = Carbon::parse($tokenData['expires_at']);
        $now = Carbon::now();
        
        // Si expira en menos de 10 minutos, renovar proactivamente
        $minutesUntilExpiry = $now->diffInMinutes($expiresAt, false);
        
        Log::debug("Token para usuario expira en {$minutesUntilExpiry} minutos");
        
        return $minutesUntilExpiry <= 10;
    }

    /**
     * Obtiene un nuevo token de la API
     */
    private function obtainNewToken(): ?array
    {
        $url = env('API_TOKEN');
        $user = env('API_USER');
        $password = env('API_PASSWORD');

        if (!$url || !$user || !$password) {
            Log::error("Configuración de API incompleta");
            return null;
        }

        try {
            $response = Http::timeout(10)->post($url, [
                'username' => $user,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Calcular tiempo de expiración: now + expiry_time
                $expiresIn = $data['expires_in'] ?? self::DEFAULT_TOKEN_TTL;
                $expiresAt = now()->addSeconds($expiresIn);
                
                return [
                    'access_token' => $data['access_token'] ?? null,
                    'refresh_token' => $data['refresh_token'] ?? null,
                    'expires_at' => $expiresAt,
                    'expires_in' => $expiresIn,
                    'obtained_at' => now()
                ];
            }

            Log::error("Error API token: " . $response->status() . " - " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("Excepción obteniendo token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Renueva un token usando refresh_token
     */
    private function refreshToken(string $refreshToken): ?array
    {
        $refreshUrl = env('API_REFRESH');
        if (!$refreshUrl) {
            Log::info("No hay URL de refresh configurada (API_REFRESH), obteniendo token nuevo");
            return $this->obtainNewToken();
        }

        try {
            Log::info("Renovando token usando refresh_token con URL: {$refreshUrl}");
            
            $response = Http::timeout(10)->post($refreshUrl, [
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
                'client_id' => env('API_CLIENT_ID'),
                'client_secret' => env('API_CLIENT_SECRET'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Calcular tiempo de expiración: now + expiry_time
                $expiresIn = $data['expires_in'] ?? self::DEFAULT_TOKEN_TTL;
                $expiresAt = now()->addSeconds($expiresIn);
                
                Log::info("Token renovado exitosamente usando refresh_token, expira en {$expiresIn} segundos");
                
                return [
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'] ?? $refreshToken, // Mantener el anterior si no hay nuevo
                    'expires_at' => $expiresAt,
                    'expires_in' => $expiresIn,
                    'obtained_at' => now()
                ];
            }

            Log::warning("Refresh token falló (status: {$response->status()}), obteniendo token nuevo");
            return $this->obtainNewToken();

        } catch (\Exception $e) {
            Log::error("Error en refresh token: " . $e->getMessage());
            return $this->obtainNewToken();
        }
    }

    /**
     * Almacena token en cache para el usuario
     */
    private function cacheTokenForUser(User $user, array $tokenData): void
    {
        $cacheKey = self::CACHE_PREFIX . $user->id;
        
        // Calcular TTL dinámico basado en expiración del token
        $expiresAt = Carbon::parse($tokenData['expires_at']);
        $ttl = $expiresAt->diffInSeconds(now());
        
        // Reducir TTL en 10 minutos (600 segundos) para renovar proactivamente usando refresh_token
        $ttl = max($ttl - 600, 60);
        
        Cache::put($cacheKey, $tokenData, $ttl);
        
        Log::info("Token almacenado en cache para usuario {$user->id} con TTL de {$ttl} segundos (renovación proactiva a los " . ($ttl/60) . " min)");
    }

    /**
     * Obtiene token global (sin usuario específico)
     */
    private function getGlobalToken(): ?string
    {
        $tokenData = Cache::get(self::GLOBAL_TOKEN_KEY);
        
        if ($tokenData && $this->isTokenValid($tokenData)) {
            // Verificar si está cerca de expirar para renovar proactivamente
            if ($this->isTokenNearExpiry($tokenData)) {
                Log::info("Token global cerca del vencimiento, renovando proactivamente");
                
                if (isset($tokenData['refresh_token'])) {
                    $newTokenData = $this->refreshToken($tokenData['refresh_token']);
                    if ($newTokenData) {
                        // Calcular TTL para cache global
                        $expiresAt = Carbon::parse($newTokenData['expires_at']);
                        $ttl = max($expiresAt->diffInSeconds(now()) - 600, 60); // 10 min antes
                        
                        Cache::put(self::GLOBAL_TOKEN_KEY, $newTokenData, $ttl);
                        Log::info("Token global renovado proactivamente");
                        return $newTokenData['access_token'];
                    }
                }
            }
            
            return $tokenData['access_token'];
        }

        // Obtener nuevo token global
        $tokenData = $this->obtainNewToken();
        if ($tokenData) {
            // Calcular TTL para cache global
            $expiresAt = Carbon::parse($tokenData['expires_at']);
            $ttl = max($expiresAt->diffInSeconds(now()) - 600, 60); // 10 min antes del vencimiento
            
            Cache::put(self::GLOBAL_TOKEN_KEY, $tokenData, $ttl);
            return $tokenData['access_token'];
        }

        return null;
    }

    /**
     * Invalida token de usuario (logout)
     */
    public function invalidateUserToken(User $user): void
    {
        $cacheKey = self::CACHE_PREFIX . $user->id;
        Cache::forget($cacheKey);
        
        Log::info("Token invalidado en memoria para usuario {$user->id}");
    }

    /**
     * Limpia todos los tokens del cache
     */
    public function clearAllTokens(): void
    {
        // Obtener todas las claves de tokens de usuarios
        $pattern = self::CACHE_PREFIX . '*';
        
        // Laravel no tiene un método directo para esto, pero podemos usar tags si están habilitados
        // o simplemente limpiar tokens por usuario activo
        Cache::forget(self::GLOBAL_TOKEN_KEY);
        
        Log::info("Tokens globales limpiados del cache");
    }

    /**
     * Obtiene información del token del usuario
     */
    public function getTokenInfo(User $user): ?array
    {
        $cacheKey = self::CACHE_PREFIX . $user->id;
        $tokenData = Cache::get($cacheKey);
        
        if (!$tokenData) {
            return null;
        }

        return [
            'has_token' => true,
            'is_valid' => $this->isTokenValid($tokenData),
            'expires_at' => $tokenData['expires_at'],
            'expires_in_human' => Carbon::parse($tokenData['expires_at'])->diffForHumans(),
            'has_refresh_token' => !empty($tokenData['refresh_token']),
            'obtained_at' => $tokenData['obtained_at'] ?? null,
        ];
    }

    /**
     * Renueva el token del usuario forzadamente
     */
    public function forceRefreshUserToken(User $user): bool
    {
        try {
            $tokenData = $this->obtainNewToken();
            if ($tokenData) {
                $this->cacheTokenForUser($user, $tokenData);
                Log::info("Token forzadamente renovado para usuario {$user->id}");
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Error en renovación forzada de token: " . $e->getMessage());
            return false;
        }
    }
}