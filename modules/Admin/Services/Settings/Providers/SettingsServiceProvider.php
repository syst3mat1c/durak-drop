<?php

namespace Modules\Admin\Services\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Admin\Services\Settings\Services\SettingsAdminService;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
    }

    public function register()
    {
        $this->app->singleton(SettingsAdminService::class);
    }

    public function registerRoutes()
    {
        require __DIR__ . '/../routes/web.php';
    }
}
