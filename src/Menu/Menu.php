<?php

namespace Hexatex\LaravelMenu\Menu;

use Hexatex\LaravelMenu\MenuItem\MenuItem;
use Hexatex\LaravelMenu\Menu\Contracts\Menu as MenuContract;
use Hexatex\LaravelMisc\Model;
use Illuminate\Contracts\Database\Query\Builder;

class Menu extends Model implements MenuContract
{
    protected $fillable = ['display_name', 'code'];

    /*
     * MenuItemParent
     */
    public function menuItems(): Builder
    {
        return $this->morphMany(MenuItem::class, 'parent');
    }

    /*
     * Scopes
     */
    /** Scope by code */
    public function scopeByCode(Builder $query, string $code): void
    {
        $query->where('code', $code);
    }
}
