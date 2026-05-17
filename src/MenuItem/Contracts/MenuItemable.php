<?php

namespace Hexatex\LaravelMenu\MenuItem\Contracts;

use Hexatex\LaravelMisc\Contracts\Model;
use Hexatex\LaravelSlug\Slug\Contracts\Sluggable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Relationships
 * @property-read MenuItem[]|Collection $menuItems
 */
interface MenuItemable extends Model, Sluggable
{
    public function menuItems(): Builder;
}
