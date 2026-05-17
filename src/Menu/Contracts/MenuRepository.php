<?php

namespace Hexatex\LaravelMenu\Menu\Contracts;

use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MenuRepository
{
    /**
     * @return Menu[]|Collection|LengthAwarePaginator
     */
    public function index(array $filters): Collection|LengthAwarePaginator;
    public function store(array $fill): Menu;
    public function update(array $fill, Menu $menu): void;
    public function destroy(Menu $menu): void;
}
