<?php

namespace Hexatex\LaravelMenu;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hexatex\LaravelMenu\Commands\MenuCommand;

class MenuServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('menu')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_menu_table')
            ->hasCommand(MenuCommand::class);
    }
}
