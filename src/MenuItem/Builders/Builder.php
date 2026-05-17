<?php

namespace Hexatex\LaravelMenu\MenuItem\Builders;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;

interface Builder
{
    public function __construct(array $fill, MenuItem $model);
    public function forStore(MenuItemParent $menuItemParent): void;
    public function forUpdate(MenuItemParent $menuItemParent): void;
}
