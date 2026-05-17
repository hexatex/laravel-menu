<?php

namespace Hexatex\LaravelMenu\Menu;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemService;
use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMenu\Menu\Contracts\MenuRepository;
use Hexatex\LaravelMenu\Menu\Contracts\MenuService as MenuServiceContract;
use Hexatex\LaravelMenu\Menu\Requests\Admin\IndexRequest;
use Hexatex\LaravelMenu\Menu\Requests\Admin\MenuRequest;
use Hexatex\LaravelService\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MenuService extends Service implements MenuServiceContract
{
    public function __construct(MenuRepository $repository, protected MenuItemService $menuItemService)
    {
        $this->repository = $repository;
    }

    /**
     * @see IndexRequest
     * @return Menu[]|Collection|LengthAwarePaginator
     */
    public function index(array $filters): Collection|LengthAwarePaginator
    {
        return $this->repository->index($filters);
    }

    /**
     * @see MenuRequest
     */
    public function store(array $fill): Menu
    {
        $menu = $this->repository->store($fill);

        $this->menuItemService->storeUpdateDestroy($fill['menuItems'] ?? [], $menu->menuItems, $menu);

        return $menu;
    }

    /**
     * @see MenuRequest
     */
    public function update(array $fill, Menu $menu): void
    {
        $this->repository->update($fill, $menu);

        $this->menuItemService->storeUpdateDestroy($fill['menuItems'] ?? [], $menu->menuItems, $menu);
    }

    public function destroy(Menu $menu): void
    {
        $this->repository->destroy($menu);

        $this->menuItemService->storeUpdateDestroy([], $menu->menuItems, $menu);
    }
}
