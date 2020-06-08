<?php

namespace App\Services\Billing\Providers;

use App\Services\Billing\Services\BillingService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerViews();
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BillingService::class);
    }

    /**
     * @return void
     */
    protected function registerRoutes()
    {
        Route::middleware('api')->group(__DIR__ . '/../Routes/api.php');
        Route::middleware('web')->group(__DIR__ . '/../Routes/web.php');
    }

    /**
     * @return void
     */
    protected function registerViews()
    {
        $this->loadViewsFrom([__DIR__ . '/../Resources/views/'], 'billing');
    }
}
