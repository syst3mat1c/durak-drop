<?php

namespace App\Services\RedisSockets;

use Illuminate\Support\ServiceProvider;

class RedisSocketsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RedisBroadcaster::class);
    }
}
