<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ApiTokenService;
use Illuminate\Console\Command;

class ClearUserTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:clear-user {email : Email del usuario}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia el token de un usuario específico del cache';

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

        $this->info("🧹 Limpiando token para: {$user->name} ({$user->email})");
        
        try {
            // Verificar si tiene token antes de limpiar
            $tokenInfo = $user->getTokenInfo();
            $hadToken = $tokenInfo !== null;
            
            // Invalidar token
            $user->invalidateApiToken();
            
            if ($hadToken) {
                $this->info('✅ Token limpiado exitosamente');
                $this->line('  El usuario deberá obtener un nuevo token en el próximo login o uso de API');
            } else {
                $this->info('ℹ️  El usuario no tenía tokens en cache');
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error limpiando token: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}