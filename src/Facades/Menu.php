<?php

namespace Hexatex\LaravelMenu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hexatex\LaravelMenu\MenuService
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Hexatex\LaravelMenu\MenuService::class;
    }
}
