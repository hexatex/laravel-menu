<?php

namespace Hexatex\LaravelMenu\MenuItem;

use Hexatex\LaravelMenu\MenuItem\Builders\BuilderFactory;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemRepository as MenuItemRepositoryContract;
use Hexatex\LaravelMenu\MenuItem\MenuItem as ConcreteMenuItem;
use Hexatex\LaravelMenu\MenuItem\Requests\Admin\IndexRequest;
use Hexatex\LaravelMenu\MenuItem\Requests\Admin\MenuItemRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MenuItemRepository implements MenuItemRepositoryContract
{
    public function __construct(protected BuilderFactory $builderFactory)
    {}

    /**
     * @see MenuItemRequest
     */
    public function store(array $fill, MenuItemParent $menuItemParent): MenuItem
    {
        $menuItem = $this->create();
        $builder = $this->builderFactory->create($fill, $menuItem);
        $builder->forStore($menuItemParent);
        $menuItem->save();

        return $menuItem;
    }

    /**
     * @see MenuItemRequest
     */
    public function update(array $fill, MenuItem $menuItem, MenuItemParent $menuItemParent): void
    {
        $builder = $this->builderFactory->create($fill, $menuItem);
        $builder->forUpdate($menuItemParent);
        $menuItem->save();
    }

    public function destroy(MenuItem $menuItem): void
    {
        $menuItem->delete();
    }

    /*
     * Protected Methods
     */
    protected function getModel(): string
    {
        return ConcreteMenuItem::class;
    }

    protected function create(): MenuItem
    {
        return new ($this->getModel());
    }
}
