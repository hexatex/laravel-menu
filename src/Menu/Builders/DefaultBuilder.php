<?php

namespace Hexatex\LaravelMenu\Menu\Builders;

use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMisc\Patterns\Builder as BaseBuilder;
use Illuminate\Support\Carbon;

class DefaultBuilder extends BaseBuilder implements Builder
{
    public function __construct(array $fill, Menu $model)
    {
        $this->fill = $fill;
        $this->model = $model;
    }

    public function forStore(): void
    {
        $this->fill();
    }

    public function forUpdate(): void
    {
        $this->fill();
    }
}
