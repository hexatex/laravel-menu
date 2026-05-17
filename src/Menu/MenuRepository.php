<?php

namespace Hexatex\LaravelMenu\Menu;

use Hexatex\LaravelMenu\Menu\Builders\BuilderFactory;
use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMenu\Menu\Contracts\MenuRepository as MenuRepositoryContract;
use Hexatex\LaravelMenu\Menu\Menu as ConcreteMenu;
use Hexatex\LaravelMenu\Menu\Requests\Admin\IndexRequest;
use Hexatex\LaravelMenu\Menu\Requests\Admin\MenuRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MenuRepository implements MenuRepositoryContract
{
    protected $builderFactory;

    public function __construct(BuilderFactory $builderFactory)
    {
        $this->builderFactory = $builderFactory;
    }

    /**
     * @see IndexRequest
     * @return Menu[]|Collection|LengthAwarePaginator
     */
    public function index(array $filters): Collection|LengthAwarePaginator
    {
        return $this->getModel()::filter($filters)
            ->paginate($filters['rowsPerPage'] ?? config('menu.default-rows-per-page'));
    }

    /**
     * @see MenuRequest
     */
    public function store(array $fill): Menu
    {
        $menu = $this->create();
        $builder = $this->builderFactory->create($fill, $menu);
        $builder->forStore();
        $menu->save();

        return $menu;
    }

    /**
     * @see MenuRequest
     */
    public function update(array $fill, Menu $menu): void
    {
        $builder = $this->builderFactory->create($fill, $menu);
        $builder->forUpdate();
        $menu->save();
    }

    public function destroy(Menu $menu): void
    {
        $menu->delete();
    }

    /*
     * Protected Methods
     */
    protected function getModel(): string
    {
        return ConcreteMenu::class;
    }

    protected function create(): Menu
    {
        return new ($this->getModel());
    }
}
