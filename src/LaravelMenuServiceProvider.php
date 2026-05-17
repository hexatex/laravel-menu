<?php

namespace Hexatex\LaravelMenu;

use Hexatex\LaravelMenu\MenuItem\Builders\Builder as MenuItemBuilder;
use Hexatex\LaravelMenu\MenuItem\Builders\BuilderFactory as MenuItemBuilderFactory;
use Hexatex\LaravelMenu\MenuItem\Builders\DefaultBuilder as MenuItemDefaultBuilder;
use Hexatex\LaravelMenu\MenuItem\Builders\DefaultBuilderFactory as MenuItemDefaultBuilderFactory;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemRepository;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemService;
use Hexatex\LaravelMenu\MenuItem\MenuItem as ConcreteMenuItem;
use Hexatex\LaravelMenu\MenuItem\MenuItemRepository as ConcreteMenuItemRepository;
use Hexatex\LaravelMenu\MenuItem\MenuItemService as ConcreteMenuItemService;
use Hexatex\LaravelMenu\Menu\Builders\Builder;
use Hexatex\LaravelMenu\Menu\Builders\BuilderFactory;
use Hexatex\LaravelMenu\Menu\Builders\DefaultBuilder;
use Hexatex\LaravelMenu\Menu\Builders\DefaultBuilderFactory;
use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMenu\Menu\Contracts\MenuRepository;
use Hexatex\LaravelMenu\Menu\Contracts\MenuService;
use Hexatex\LaravelMenu\Menu\Menu as ConcreteMenu;
use Hexatex\LaravelMenu\Menu\MenuRepository as ConcreteMenuRepository;
use Hexatex\LaravelMenu\Menu\MenuService as ConcreteMenuService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelMenuServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'laravel-menu' => ConcreteMenu::class,
            'laravel-menu-item' => ConcreteMenuItem::class,
        ]);

        Route::macro('bindMenuModel', function () {
            Route::model('menu', ConcreteMenu::class);
        });

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'hexatex');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'hexatex');

        $this->loadRoutesFrom(__DIR__.'/../routes/menu.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            $this->bootForConsole();

            if ($this->app->environment('testing')) {
                $this->loadMigrationsFrom(__DIR__.'/../database/migrations/testing');
            }
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/menu.php', 'menu');
        $this->mergeConfigFrom(__DIR__.'/../config/menu-item.php', 'menu-item');

        // Menu
        $this->app->bind(Menu::class, ConcreteMenu::class);
        $this->app->bind(MenuRepository::class, ConcreteMenuRepository::class);
        $this->app->bind(MenuService::class, ConcreteMenuService::class);
        $this->app->bind(Builder::class, DefaultBuilder::class);
        $this->app->bind(BuilderFactory::class, DefaultBuilderFactory::class);

        // MenuItem
        $this->app->bind(MenuItem::class, ConcreteMenuItem::class);
        $this->app->bind(MenuItemRepository::class, ConcreteMenuItemRepository::class);
        $this->app->bind(MenuItemService::class, ConcreteMenuItemService::class);
        $this->app->bind(MenuItemBuilder::class, MenuItemDefaultBuilder::class);
        $this->app->bind(MenuItemBuilderFactory::class, MenuItemDefaultBuilderFactory::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/menu.php' => config_path('menu.php'),
            __DIR__.'/../config/menu-item.php' => config_path('menu-item.php'),
        ], 'laravel-menu.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/hexatex'),
        ], 'menu.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/hexatex'),
        ], 'menu.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/hexatex'),
        ], 'menu.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
