<?php

namespace Hexatex\LaravelMenu\MenuItem\Contracts;

interface MenuItemRepository
{
    public function store(array $fill, MenuItemParent $menuItemParent): MenuItem;
    public function update(array $fill, MenuItem $menu, MenuItemParent $menuItemParent): void;
    public function destroy(MenuItem $menu): void;
}
