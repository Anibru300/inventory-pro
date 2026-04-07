<?php

namespace App\Providers;

use App\Services\InventoryCostingService;
use App\Services\InventoryService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar servicios de inventario como singletons
        $this->app->singleton(InventoryCostingService::class, function ($app) {
            return new InventoryCostingService();
        });

        $this->app->singleton(InventoryService::class, function ($app) {
            return new InventoryService(
                $app->make(InventoryCostingService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
