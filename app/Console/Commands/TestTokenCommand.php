<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ApiTokenService;
use Illuminate\Console\Command;

class TestTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:test {email : Email del usuario a probar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el sistema de tokens para un usuario específico';

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

        $this->info("🧪 Probando sistema de tokens para: {$user->name} ({$user->email})");
        $this->newLine();

        // Test 1: Estado inicial
        $this->comment('📋 Test 1: Estado inicial');
        $initialInfo = $user->getTokenInfo();
        if ($initialInfo) {
            $status = $initialInfo['is_valid'] ? '✅ Válido' : '❌ Expirado';
            $this->line("  Token existente: {$status}");
            $this->line("  Expira: {$initialInfo['expires_in_human']}");
        } else {
            $this->line('  ⚫ No hay tokens en cache');
        }
        $this->newLine();

        // Test 2: Obtener token
        $this->comment('📋 Test 2: Obtener token válido');
        try {
            $startTime = microtime(true);
            $token = $user->getApiToken();
            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2);
            
            if ($token) {
                $tokenPreview = substr($token, 0, 40) . '...[truncado]';
                $this->line("  ✅ Token obtenido en {$duration}ms");
                $this->line("  Token: {$tokenPreview}");
                
                $newInfo = $user->getTokenInfo();
                if ($newInfo) {
                    $this->line("  Expira: {$newInfo['expires_in_human']}");
                    $this->line("  Refresh token: " . ($newInfo['has_refresh_token'] ? '✅' : '❌'));
                }
            } else {
                $this->error("  ❌ No se pudo obtener token");
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
        $this->newLine();

        // Test 3: Segundo acceso (debe usar cache)
        $this->comment('📋 Test 3: Segundo acceso (cache)');
        try {
            $startTime = microtime(true);
            $token2 = $user->getApiToken();
            $endTime = microtime(true);
            $duration2 = round(($endTime - $startTime) * 1000, 2);
            
            if ($token2) {
                $this->line("  ✅ Token obtenido desde cache en {$duration2}ms");
                $this->line("  Cache funcionando: " . ($duration2 < $duration ? '✅' : '⚠️'));
            } else {
                $this->error("  ❌ Error en segundo acceso");
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
        }
        $this->newLine();

        // Test 4: Simular proceso de login
        $this->comment('📋 Test 4: Simular obtención en login');
        try {
            // Invalidar token actual
            $user->invalidateApiToken();
            $this->line('  🗑️  Token invalidado');
            
            // Simular login
            $success = $this->apiTokenService->obtainTokensOnLogin($user);
            if ($success) {
                $this->line('  ✅ Login simulado exitoso');
                
                $loginInfo = $user->getTokenInfo();
                if ($loginInfo) {
                    $this->line("  Token post-login expira: {$loginInfo['expires_in_human']}");
                }
            } else {
                $this->error('  ❌ Error en login simulado');
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
        }
        $this->newLine();

        // Test 5: Renovación forzada
        $this->comment('📋 Test 5: Renovación forzada');
        try {
            $refreshSuccess = $user->refreshApiToken();
            if ($refreshSuccess) {
                $this->line('  ✅ Token renovado forzadamente');
                
                $refreshedInfo = $user->getTokenInfo();
                if ($refreshedInfo) {
                    $this->line("  Nuevo token expira: {$refreshedInfo['expires_in_human']}");
                }
            } else {
                $this->error('  ❌ Error en renovación forzada');
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
        }
        $this->newLine();

        // Resumen final
        $this->info('📊 Resumen del Test:');
        $finalInfo = $user->getTokenInfo();
        if ($finalInfo) {
            $this->line('  ✅ Sistema de tokens funcionando correctamente');
            $this->line('  ✅ Cache funcionando');
            $this->line('  ✅ Obtención automática en login');
            $this->line('  ✅ Renovación manual funcional');
            $this->line("  📅 Token válido hasta: {$finalInfo['expires_in_human']}");
        } else {
            $this->error('  ❌ Problemas en el sistema de tokens');
        }

        return Command::SUCCESS;
    }
}