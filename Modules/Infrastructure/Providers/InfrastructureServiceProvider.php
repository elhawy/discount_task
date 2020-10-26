<?php

namespace Modules\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Infrastructure\Services\ActivityAuditService;
use Modules\Infrastructure\Services\Interfaces\ActivityAuditServiceInterface;
use Modules\Infrastructure\Services\Interfaces\MessageServiceInterface;
use Modules\Infrastructure\Services\Interfaces\PaymentAuthServiceInterface;
use Modules\Infrastructure\Services\MessageService;
use Modules\Infrastructure\Services\PaymentAuthService;

class InfrastructureServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
