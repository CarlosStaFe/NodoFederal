<?php

namespace App\Console\Commands;

use App\Services\ApiTokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearAllTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:clear-all {--force : Forzar limpieza sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todos los tokens API del cache';

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
        $force = $this->option('force');
        
        if (!$force && !$this->confirm('⚠️  ¿Estás seguro de limpiar TODOS los tokens del cache? Los usuarios tendrán que volver a autenticarse.')) {
            $this->info('Operación cancelada');
            return Command::SUCCESS;
        }

        $this->info('🧹 Limpiando todos los tokens del cache...');
        
        try {
            // Limpiar token global
            $this->apiTokenService->clearAllTokens();
            
            // Intentar limpiar tokens de usuarios específicos
            // Nota: Laravel no tiene una forma directa de eliminar por patrón,
            // pero podemos limpiar el cache completo o usar tags si están configurados
            
            $cacheDriver = config('cache.default');
            $this->line("  Cache driver: {$cacheDriver}");
            
            // Si es file o array cache, podemos ser más agresivos
            if (in_array($cacheDriver, ['file', 'array'])) {
                $this->warn('  Limpiando cache completo para asegurar eliminación de todos los tokens...');
                Cache::flush();
                $this->info('✅ Cache completo limpiado');
            } else {
                $this->info('✅ Token global limpiado');
                $this->line('  Para eliminar completamente todos los tokens de usuarios,');
                $this->line('  considera usar: php artisan cache:clear');
            }
            
            $this->newLine();
            $this->info('🔄 Los usuarios obtendrán nuevos tokens automáticamente en su próximo login o uso de API');
            
        } catch (\Exception $e) {
            $this->error("❌ Error limpiando tokens: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}