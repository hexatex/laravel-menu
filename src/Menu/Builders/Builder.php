<?php

namespace Hexatex\LaravelMenu\Menu\Builders;

use Hexatex\LaravelMenu\Menu\Contracts\Menu;

interface Builder
{
    public function __construct(array $fill, Menu $model);
    public function forStore(): void;
    public function forUpdate(): void;
}
