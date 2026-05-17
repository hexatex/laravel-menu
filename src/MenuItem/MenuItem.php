<?php

namespace Hexatex\LaravelMenu\MenuItem;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem as MenuItemContract;
use Hexatex\LaravelMisc\Model;
use Illuminate\Contracts\Database\Query\Builder;

class MenuItem extends Model implements MenuItemContract
{
    protected $fillable = ['display_name'];

    /*
     * MenuItemParent
     */
    public function menuItems(): Builder
    {
        return $this->morphMany(MenuItem::class, 'parent');
    }

    /*
     * Relationships
     */
    public function parent(): Builder
    {
        return $this->morphTo();
    }

    public function menuItemable(): Builder
    {
        return $this->morphTo();
    }
}
