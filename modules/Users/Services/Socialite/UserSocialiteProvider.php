<?php

namespace Modules\Users\Services\Socialite;

use Illuminate\Support\ServiceProvider;

class UserSocialiteProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('user-socialite', UserSocialiteService::class);
    }
}
