<?php

namespace Hexatex\LaravelMenu\MenuItem\Contracts;

use Hexatex\LaravelMisc\Contracts\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Query\Builder;

/**
 * Relationships
 * @property-read MenuItem[]|Collection $menuItems
 */
interface MenuItemParent extends Model
{
    public function menuItems(): Builder;
}
