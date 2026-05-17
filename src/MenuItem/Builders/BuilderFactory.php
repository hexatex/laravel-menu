<?php

namespace Hexatex\LaravelMenu\MenuItem\Builders;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;

interface BuilderFactory
{
    public function create(array $fill, MenuItem $menuItem): Builder;
}
