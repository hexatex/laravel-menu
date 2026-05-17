<?php

namespace Hexatex\LaravelMenu\Menu\Contracts;

use Hexatex\LaravelService\Contracts\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MenuService extends Service
{
    /**
     * @return Menu[]|Collection|LengthAwarePaginator
     */
    public function index(array $filters): Collection|LengthAwarePaginator;
    public function store(array $fill): Menu;
    public function update(array $fill, Menu $image): void;
    public function destroy(Menu $image): void;
}
