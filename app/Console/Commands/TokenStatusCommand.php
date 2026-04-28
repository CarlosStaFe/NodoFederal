<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ApiTokenService;
use Illuminate\Console\Command;

class TokenStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:status {email : Email del usuario a verificar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el estado de los tokens de un usuario específico en memoria';

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

        $this->info("🔍 Estado de tokens para: {$user->name} ({$user->email})");
        $this->newLine();

        // Información del token desde el servicio
        $tokenInfo = $user->getTokenInfo();
        
        if (!$tokenInfo) {
            $this->comment('💾 Estado en Cache/Memoria:');
            $this->line('  ❌ No hay tokens en memoria para este usuario');
            $this->newLine();
        } else {
            $this->comment('💾 Estado en Cache/Memoria:');
            $this->line('  ✅ Token disponible en cache');
            $status = $tokenInfo['is_valid'] ? '✅ Válido' : '❌ Expirado';
            $this->line("  Estado: {$status}");
            $this->line("  Expira: {$tokenInfo['expires_in_human']}");
            
            $refreshStatus = $tokenInfo['has_refresh_token'] ? '✅ Disponible' : '❌ No disponible';
            $this->line("  Refresh Token: {$refreshStatus}");
            
            if ($tokenInfo['obtained_at']) {
                $obtainedAt = \Carbon\Carbon::parse($tokenInfo['obtained_at']);
                $this->line("  Obtenido: {$obtainedAt->diffForHumans()}");
            }
            $this->newLine();
        }

        // Test de obtención de token
        $this->comment('🧪 Test de Obtención de Token:');
        try {
            $this->line('  Obteniendo token válido...');
            $token = $user->getApiToken();
            
            if ($token) {
                $tokenPreview = substr($token, 0, 30) . '...[truncado]';
                $this->line("  ✅ Token obtenido: {$tokenPreview}");
                
                // Verificar info actualizada
                $newTokenInfo = $user->getTokenInfo();
                if ($newTokenInfo) {
                    $this->line("  ✅ Expira: {$newTokenInfo['expires_in_human']}");
                }
            } else {
                $this->line('  ❌ No se pudo obtener token válido');
            }
        } catch (\Exception $e) {
            $this->line("  ❌ Error: " . $e->getMessage());
        }

        $this->newLine();
        
        // Acciones disponibles
        $this->comment('🛠️  Acciones Disponibles:');
        
        if ($this->confirm('¿Deseas invalidar el token actual de este usuario?')) {
            try {
                $user->invalidateApiToken();
                $this->info('✅ Token invalidado exitosamente');
            } catch (\Exception $e) {
                $this->error("❌ Error invalidando token: " . $e->getMessage());
            }
        }
        
        if ($this->confirm('¿Deseas forzar la renovación del token?')) {
            try {
                $success = $user->refreshApiToken();
                if ($success) {
                    $this->info('✅ Token renovado exitosamente');
                    
                    // Mostrar nuevo estado
                    $newTokenInfo = $user->getTokenInfo();
                    if ($newTokenInfo) {
                        $this->line("  Nuevo token expira: {$newTokenInfo['expires_in_human']}");
                    }
                } else {
                    $this->error('❌ Error renovando token');
                }
            } catch (\Exception $e) {
                $this->error("❌ Error: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}