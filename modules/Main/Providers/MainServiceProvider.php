<?php

namespace Modules\Main\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Main\Entities\Item;
use Modules\Main\Observers\ItemObserver;
use Modules\Main\Repositories\{
    BoxItemRepository, BoxRepository, BuyerItemRepository, DepositRepository, ItemRepository, OrderRepository, QuestionRepository, CategoryRepository, PromocodeRepository
};
use Modules\Main\Services\LiveFeed\LiveFeedItem;
use Modules\Main\Services\LiveFeed\LiveFeedService;

class MainServiceProvider extends ServiceProvider
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
        //
    }

    /**
     * @return void
     */
    protected function registerObservers()
    {
        Item::observe(ItemObserver::class);
    }

    /**
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->singleton(QuestionRepository::class);
        $this->app->singleton(BoxItemRepository::class);
        $this->app->singleton(BoxRepository::class);
        $this->app->singleton(ItemRepository::class);
        $this->app->singleton(OrderRepository::class);
        $this->app->singleton(CategoryRepository::class);
        $this->app->singleton(PromocodeRepository::class);
        $this->app->singleton(LiveFeedService::class);
        $this->app->singleton(DepositRepository::class);
    }

    /**
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('main.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'main'
        );
    }

    /**
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/main');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/main';
        }, \Config::get('view.paths')), [$sourcePath]), 'main');
    }

    /**
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/main');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'main');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'main');
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
