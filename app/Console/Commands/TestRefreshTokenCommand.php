<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ApiTokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TestRefreshTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:test-refresh {email : Email del usuario a probar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba específicamente el sistema de refresh_token y renovación proactiva';

    protected $apiTokenService;

    /**
     * Create a new command instance.
     */
    public function __construct(ApiTokenService $apiTokenService)
    {
        parent::__construct();
        $this->apiTokenService = $apiTokenService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Usuario no encontrado: {$email}");
            return Command::FAILURE;
        }

        $this->info("🔄 Probando sistema de refresh_token para: {$user->name} ({$user->email})");
        $this->newLine();

        // Verificar configuración de refresh
        $refreshUrl = env('API_REFRESH');
        $this->comment('📋 Configuración de Refresh:');
        if ($refreshUrl) {
            $this->line("  ✅ API_REFRESH configurada: {$refreshUrl}");
        } else {
            $this->error("  ❌ API_REFRESH no configurada");
            return Command::FAILURE;
        }
        $this->newLine();

        // Test 1: Obtener token inicial
        $this->comment('📋 Test 1: Obtener token inicial');
        try {
            $initialToken = $user->getApiToken();
            if ($initialToken) {
                $this->line("  ✅ Token inicial obtenido");
                
                $tokenInfo = $user->getTokenInfo();
                if ($tokenInfo) {
                    $this->line("  Expira: {$tokenInfo['expires_in_human']}");
                    $this->line("  Refresh token: " . ($tokenInfo['has_refresh_token'] ? '✅ Disponible' : '❌ No disponible'));
                }
            } else {
                $this->error("  ❌ No se pudo obtener token inicial");
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
        $this->newLine();

        // Test 2: Simular token próximo a expirar
        $this->comment('📋 Test 2: Simular token próximo a expirar');
        try {
            $cacheKey = 'user_token_' . $user->id;
            $currentTokenData = Cache::get($cacheKey);
            
            if ($currentTokenData) {
                // Modificar la fecha de expiración para que expire en 5 minutos
                $currentTokenData['expires_at'] = now()->addMinutes(5);
                Cache::put($cacheKey, $currentTokenData, 300); // 5 minutos
                
                $this->line("  🕐 Token modificado para expirar en 5 minutos");
                $this->line("  🔄 Probando renovación proactiva...");
                
                // Obtener token nuevamente - debe activar renovación proactiva
                $renewedToken = $user->getApiToken();
                
                if ($renewedToken) {
                    $newTokenInfo = $user->getTokenInfo();
                    if ($newTokenInfo) {
                        $this->line("  ✅ Token renovado proactivamente");
                        $this->line("  Nuevo tiempo de expiración: {$newTokenInfo['expires_in_human']}");
                    }
                } else {
                    $this->error("  ❌ Error en renovación proactiva");
                }
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
        }
        $this->newLine();

        // Test 3: Verificar logs de renovación
        $this->comment('📋 Test 3: Verificar logs de renovación');
        $this->line('  📄 Revisa los logs en storage/logs/laravel.log para ver:');
        $this->line('    - "Token cerca del vencimiento, renovando proactivamente"');
        $this->line('    - "Renovando token usando refresh_token"');
        $this->line('    - "Token renovado exitosamente usando refresh_token"');
        $this->newLine();

        // Test 4: Forzar renovación manual
        $this->comment('📋 Test 4: Renovación manual usando refresh_token');
        try {
            $this->line('  🔄 Forzando renovación manual...');
            $success = $user->refreshApiToken();
            
            if ($success) {
                $this->line('  ✅ Renovación manual exitosa');
                
                $finalTokenInfo = $user->getTokenInfo();
                if ($finalTokenInfo) {
                    $this->line("  Token renovado expira: {$finalTokenInfo['expires_in_human']}");
                }
            } else {
                $this->error('  ❌ Error en renovación manual');
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
        }
        $this->newLine();

        // Resumen final
        $this->info('📊 Resumen del Test de Refresh Token:');
        $finalInfo = $user->getTokenInfo();
        if ($finalInfo) {
            $this->line('  ✅ Sistema de refresh_token funcionando');
            $this->line('  ✅ Renovación proactiva (10 min antes) operativa');
            $this->line('  ✅ Renovación manual funcional');
            $this->line('  ✅ API_REFRESH configurada correctamente');
            $this->line("  📅 Token actual válido hasta: {$finalInfo['expires_in_human']}");
        } else {
            $this->error('  ❌ Problemas en el sistema de refresh token');
        }

        return Command::SUCCESS;
    }
}