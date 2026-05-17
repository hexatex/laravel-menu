<?php

namespace Hexatex\LaravelMenu\MenuItem\Contracts;

use Hexatex\LaravelService\Contracts\Service;
use Illuminate\Support\Collection;

interface MenuItemService extends Service
{
    public function store(array $fill, MenuItemParent $menuItemParent): MenuItem;
    public function update(array $fill, MenuItem $image, MenuItemParent $menuItemParent): void;
    public function destroy(MenuItem $image): void;
    public function storeUpdateDestroy(array $fill, Collection $menuItems, MenuItemParent $menuItemParent): void;
}
