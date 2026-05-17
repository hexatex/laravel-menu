<?php

namespace Hexatex\LaravelMenu\Menu\Builders;

use Hexatex\LaravelMenu\Menu\Contracts\Menu;

interface BuilderFactory
{
    public function create(array $fill, Menu $menu): Builder;
}
