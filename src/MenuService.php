<?php

namespace Hexatex\LaravelMenu;

class MenuService
{
    public function __construct(private MenuItemService $menuItemService)
    {}

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

        $this->menuItemService->storeUpdateDestroy($fill['menuItems'], $menu);
	}
}
