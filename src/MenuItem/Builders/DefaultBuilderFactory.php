<?php

namespace Hexatex\LaravelMenu\MenuItem\Builders;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;

class DefaultBuilderFactory implements BuilderFactory
{
    public function create(array $fill, MenuItem $menuItem): Builder
    {
        return new DefaultBuilder($fill, $menuItem);
    }
}
