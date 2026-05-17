<?php

namespace Hexatex\LaravelMenu\MenuItem\Builders;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMisc\Patterns\Builder as BaseBuilder;

class DefaultBuilder extends BaseBuilder implements Builder
{
    public function __construct(array $fill, MenuItem $model)
    {
        $this->fill = $fill;
        $this->model = $model;
    }

    /**
     * The 'menuItemable' id and morph_class are required in the fill array.
     *
     * @throws InvalidArgumentException
     */
    public function forStore(MenuItemParent $parent): void
    {
        $this->forUpdate($parent);
        $this->associateMorph('menuItemable');
    }

    public function forUpdate(MenuItemParent $parent): void
    {
        $this->fill();
        $this->associate('parent', $parent);
    }
}
