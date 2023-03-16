<?php

namespace Hexatex\LaravelMenu;

use Hexatex\LaravelHashId\HasHashId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Hexatex\LaravelMenu\Menu
 *
 * @property int $id
 * @property string $display_name
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|MenuItem[] $menuItems
 * @property-read int|null $menuItems_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu byCode($code)
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Hexatex\LaravelMenu\Menu whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Menu extends Model
{
    use HasHashId;

    /** @var array */
    protected $fillable = [
        'display_name',
        'code',
    ];

    /*
     * Scopes
     */
    /** Scope by code */
    public function scopeByCode(Builder $query, string $code)
    {
        $query->where('code', $code);
    }

    /*
     * Relationships
     */
    /**
     * MenuItems
     *
     * @return HasMany
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
