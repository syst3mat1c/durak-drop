<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Admin\Services\Settings\Providers\SettingsServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = false;

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->register(SettingsServiceProvider::class);
    }

    /**
     * @return void
     */
    protected function registerObservers()
    {

    }

    /**
     * @return void
     */
    protected function registerRepositories()
    {

    }

    /**
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('admin.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'admin'
        );

        $this->publishes([
            __DIR__ . '/../Config/admin_navigation.php' => config_path('admin_navigation.php')
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/admin_navigation.php', 'admin_navigation'
        );
    }

    /**
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/admin');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/admin';
        }, \Config::get('view.paths')), [$sourcePath]), 'admin');
    }

    /**
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/admin');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'admin');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'admin');
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
