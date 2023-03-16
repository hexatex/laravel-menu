<?php

namespace Hexatex\LaravelMenu;

use Hexatex\LaravelMenu\Commands\MenuCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
