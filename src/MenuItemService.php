<?php

namespace Hexatex\LaravelMenu;

use Hexatex\LaravelService\Service;

class MenuService
{
    public function store(array $fill)
    {
        $menu = new Menu($fill);
        $menu->save();

        return $menu;
    }

    public function update(array $fill, Menu $menu)
    {
        $menu->fill($fill);
        $menu->save();
    }
}
