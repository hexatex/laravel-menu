<?php

namespace Hexatex\LaravelMenu\MenuItem;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemRepository;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemService as MenuItemServiceContract;
use Hexatex\LaravelMenu\MenuItem\Requests\Admin\IndexRequest;
use Hexatex\LaravelMenu\MenuItem\Requests\Admin\MenuItemRequest;
use Hexatex\LaravelService\Service;
use Illuminate\Support\Collection;

class MenuItemService extends Service implements MenuItemServiceContract
{
    public function __construct(MenuItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @see MenuItemRequest
     */
    public function store(array $fill, MenuItemParent $menuItemParent): MenuItem
    {
        $menuItem = $this->repository->store($fill, $menuItemParent);

        $this->storeUpdateDestroy($fill['menuItems'] ?? [], $menuItem->menuItems, $menuItem);

        return $menuItem;
    }

    /**
     * @see MenuItemRequest
     */
    public function update(array $fill, MenuItem $menuItem, MenuItemParent $menuItemParent): void
    {
        $this->repository->update($fill, $menuItem, $menuItemParent);

        $this->storeUpdateDestroy($fill['menuItems'] ?? [], $menuItem->menuItems, $menuItem);
    }

    public function destroy(MenuItem $menuItem): void
    {
        $this->repository->destroy($menuItem);

        $this->storeUpdateDestroy([], $menuItem->menuItems, $menuItem);
    }

    public function storeUpdateDestroy(array $fill, Collection $menuItems, MenuItemParent $menuItemParent): void
    {
        $this->storeUpdateDestroyMany(
            $fill,
            $menuItems,
            store: fn ($fill) => $this->store($fill, $menuItemParent),
            update: fn ($fill, $menuItem) => $this->update($fill, $menuItem, $menuItemParent),
            destroy: fn ($menuItem) => $this->destroy($menuItem),
        );
    }
}
