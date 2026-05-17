<?php

namespace Hexatex\LaravelMenu\Menu\Builders;

use Hexatex\LaravelMenu\Menu\Contracts\Menu;

class DefaultBuilderFactory implements BuilderFactory
{
    public function create(array $fill, Menu $menu): Builder
    {
        return new DefaultBuilder($fill, $menu);
    }
}
