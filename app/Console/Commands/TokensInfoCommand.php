<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ApiTokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TokensInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Muestra información del sistema de tokens API en memoria';

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
        $this->info('🔐 Sistema de Tokens API en Memoria - Información');
        $this->newLine();

        // Configuración de la API
        $this->comment('📋 Configuración:');
        $apiUrl = env('API_TOKEN') ? '✅ Configurada' : '❌ No configurada';
        $apiUser = env('API_USER') ? '✅ Configurado' : '❌ No configurado';
        $apiPassword = env('API_PASSWORD') ? '✅ Configurada' : '❌ No configurada';
        $refreshUrl = env('API_REFRESH') ? '✅ Configurada' : 'ℹ️  Opcional (no configurada)';

        $this->line("  API_TOKEN: {$apiUrl}");
        $this->line("  API_USER: {$apiUser}");
        $this->line("  API_PASSWORD: {$apiPassword}");
        $this->line("  API_REFRESH: {$refreshUrl}");
        $this->newLine();

        // Estado del cache
        $this->comment('💾 Estado del Cache:');
        $this->line("  Driver: " . config('cache.default'));
        $this->line("  Store: " . config('cache.stores.' . config('cache.default') . '.driver'));
        
        // Verificar cache global
        $globalToken = Cache::get('global_api_token');
        $globalStatus = $globalToken ? '✅ Token global en cache' : 'ℹ️  Sin token global';
        $this->line("  Token global: {$globalStatus}");
        
        if ($globalToken) {
            $tokenInfo = $globalToken;
            $isValid = isset($tokenInfo['expires_at']) && 
                      \Carbon\Carbon::parse($tokenInfo['expires_at'])->isFuture();
            $validStatus = $isValid ? '✅ Válido' : '❌ Expirado';
            $this->line("  Estado global: {$validStatus}");
            
            if (isset($tokenInfo['expires_at'])) {
                $expiresAt = \Carbon\Carbon::parse($tokenInfo['expires_at']);
                $this->line("  Expira: {$expiresAt->diffForHumans()}");
            }
        }
        
        $this->newLine();

        // Estadísticas de usuarios
        $this->comment('👥 Usuarios del Sistema:');
        $totalUsers = User::count();
        $this->line("  Total de usuarios: {$totalUsers}");
        
        // Verificar algunos usuarios con tokens en cache
        $this->comment('🧪 Verificación de Tokens de Usuarios:');
        $users = User::take(5)->get();
        $tokensEnCache = 0;
        
        foreach ($users as $user) {
            $cacheKey = 'user_token_' . $user->id;
            $tokenData = Cache::get($cacheKey);
            
            if ($tokenData) {
                $tokensEnCache++;
                $isValid = isset($tokenData['expires_at']) && 
                          \Carbon\Carbon::parse($tokenData['expires_at'])->isFuture();
                $status = $isValid ? '✅' : '❌';
                $this->line("  {$user->email}: {$status}");
            } else {
                $this->line("  {$user->email}: ⚫ Sin token");
            }
        }
        
        $this->line("  Tokens activos en cache: {$tokensEnCache}");
        $this->newLine();

        // Información de seguridad
        $this->comment('🔒 Seguridad:');
        $this->line('  ✅ Tokens almacenados solo en memoria (cache)');
        $this->line('  ✅ No se persisten en base de datos');
        $this->line('  ✅ Se invalidan automáticamente al logout');
        $this->line('  ✅ Renovación automática antes del vencimiento');
        $this->line('  🔄 Renovación proactiva usando refresh_token (10 min antes)');
        $refreshConfigured = env('API_REFRESH') ? '✅ Habilitada' : '⚠️  Sin configurar';
        $this->line("  🔗 API_REFRESH: {$refreshConfigured}");
        $this->newLine();

        // Comandos disponibles
        $this->info('💡 Comandos disponibles:');
        $this->line('  php artisan tokens:status {email} - Estado de usuario específico');
        $this->line('  php artisan tokens:clear-user {email} - Limpiar token de usuario');
        $this->line('  php artisan tokens:clear-all - Limpiar todos los tokens del cache');
        $this->line('  php artisan tokens:test {email} - Probar obtención de token');        $this->line('  php artisan tokens:test-refresh {email} - Probar refresh_token específicamente');
        return Command::SUCCESS;
    }
}