<?php

namespace App\Services\UI;

use Illuminate\Support\ServiceProvider;

class UserInterfaceServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton(HeaderService::class);
    }
}
