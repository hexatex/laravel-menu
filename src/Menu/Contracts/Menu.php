<?php

namespace Hexatex\LaravelMenu\Menu\Contracts;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMisc\Contracts\Model;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $display_name
 * @property string $code // todo check that code does not exist before allowing user to fill it, alsocheck that it is in a slug format before checking if it exists
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * Scopes
 * @method static Builder|Menu byCode(string $code)
 * 
 * Relationships
 * @property-read MenuItem[]|Collection $menuItems
 */
interface Menu extends Model, MenuItemParent
{
    /* Scopes */
    public function scopeByCode(Builder $query, string $code): void;

    /* Relationships */
    public function menuItems(): Builder;
}
