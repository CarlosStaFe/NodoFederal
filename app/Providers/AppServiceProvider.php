<?php

namespace App\Providers;

use App\Services\ApiTokenService;
use App\Listeners\ObtainApiTokensOnLogin;
use App\Listeners\InvalidateTokensOnLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar el servicio de tokens API como singleton
        $this->app->singleton(ApiTokenService::class, function ($app) {
            return new ApiTokenService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar listeners para obtener tokens automáticamente al login
        Event::listen(Login::class, ObtainApiTokensOnLogin::class);
        
        // Registrar listener para invalidar tokens al logout
        Event::listen(Logout::class, InvalidateTokensOnLogout::class);
    }
}
