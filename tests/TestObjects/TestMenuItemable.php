<?php

namespace Hexatex\LaravelMenu\Tests\TestObjects;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemable;
use Hexatex\LaravelMenu\MenuItem\MenuItem;
use Hexatex\LaravelMisc\Model;
use Hexatex\LaravelSlug\Slug\Slug;
use Illuminate\Contracts\Database\Query\Builder;

class TestMenuItemable extends Model implements MenuItemable
{
    /*
     * MenuItemable
     */
    public function menuItems(): Builder
    {
        return $this->morphMany(MenuItem::class, 'menu_itemable');
    }

    /*
     * Sluggable
     */
    public function getDefaultSlug(): string
    {
        $this->test_attribute = 'test value';

        return $this->test_attribute;
    }

    public function slug(): Builder
    {
        return $this->morphOne(Slug::class, 'sluggable');
    }
}
