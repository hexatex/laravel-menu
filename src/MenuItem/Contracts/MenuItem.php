<?php

namespace Hexatex\LaravelMenu\MenuItem\Contracts;

use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMisc\Contracts\Model;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $display_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * Relationships
 * @property-read MenuItemParent $parent
 * @property-read MenuItemable $menuItemable
 */
interface MenuItem extends Model, MenuItemParent
{
    public function parent(): Builder;
    public function menuItemable(): Builder;
}
