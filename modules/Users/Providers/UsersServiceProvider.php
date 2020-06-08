<?php

namespace Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Users\Entities\Chance;
use Modules\Users\Entities\User;
use Modules\Users\Observers\ChanceObserver;
use Modules\Users\Observers\UserObserver;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Services\Socialite\UserSocialiteProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->registerObservers();
        $this->registerRepositories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->register(UserSocialiteProvider::class);
    }

    /**
     * @return void
     */
    protected function registerObservers()
    {
        User::observe(UserObserver::class);
        Chance::observe(ChanceObserver::class);
    }

    /**
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->singleton(UserRepository::class);
    }

    /**
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('users.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'users'
        );
    }

    /**
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/users');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/users';
        }, \Config::get('view.paths')), [$sourcePath]), 'users');
    }

    /**
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/users');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'users');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'users');
        }
    }

    /**
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/Factories');
        }
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
