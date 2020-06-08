<?php

namespace App\Providers;

use App\Services\Billing\Providers\BillingServiceProvider;
use App\Services\RedisSockets\RedisSocketsServiceProvider;
use App\Services\UI\UserInterfaceServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app['request']->server->set('HTTPS', $this->app->environment() != 'local');
        $this->app->register(UserInterfaceServiceProvider::class);
        $this->app->register(RedisSocketsServiceProvider::class);
        $this->app->register(BillingServiceProvider::class);
    }
}
