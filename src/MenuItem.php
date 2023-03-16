<?php

namespace Hexatex\LaravelMenu;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Hexatex\LaravelHashId\HasHashId;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Hexatex\LaravelMenu\Menu
 *
 * @property int $id
 * @property string $display_name
 * @property int $menu_itemable_id
 * @property string $menu_itemable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read MenuItemable $MenuItemable
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu query()
 * @mixin \Eloquent
 */
class MenuItem extends Model
{
    use HasHashId;

    /** @var array */
    protected $fillable = [
        'display_name',
    ];

    /*
     * Relationships
     */
    /**
     * MenuItemable
     * @return MorphTo
     */
    public function menuItemable()
    {
        return $this->morphTo;
    }
}
