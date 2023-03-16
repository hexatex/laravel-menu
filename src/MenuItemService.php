<?php

namespace Hexatex\LaravelMenu;

use Hexatex\LaravelService\Service;

class MenuItemService extends Service
{
    public function store(array $fill, Menu $menu)
    {
        $menuItem = new MenuItem($fill);
        $menuItem->menu()->associate($menu);
        $menuItem->menu_itemable_id = $fill['menu_itemable_id'];
        $menuItem->menu_itemable_type = $fill['menu_itemable_type'];
        $menuItem->save();
    }

    public function update(array $fill, MenuItem $menuItem)
    {
        $menuItem->fill($fill);
        $menuItem->menu_itemable_id = $fill['menu_itemable_id'];
        $menuItem->menu_itemable_type = $fill['menu_itemable_type'];
        $menuItem->save();
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
    }

    public function storeUpdateDestroy(array $fills, Menu $menu)
    {
        $menu->loadMissing(['menuItems']);

        $this->storeUpdateDestroyMany(
            $fills,
            $menu->menuItems,
            store: function ($fill) {
                $this->store($fill, $menu);
            },
            update: function ($fill, $menuItem) {
                $this->update($fill, $menuItem);
            },
            destroy: function ($menuItem) {
                $this->destroy($menuItem);
            },
        );
    }
}
