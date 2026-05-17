<?php

namespace Hexatex\LaravelMenu\Factories;

use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemable;
use Hexatex\LaravelMenu\MenuItem\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        return [
            'display_name' => $this->faker->sentence,
        ];
    }

    /*
     * States
     */
    public function menuItemable(?MenuItemable $menuItemable)
    {
        return $this->afterMaking(function (MenuItem $menuItem) use ($menuItemable) {
            $menuItem->menuItemable()->associate($menuItemable);
        });
    }

    public function parent(?MenuItemParent $parent)
    {
        return $this->afterMaking(function (MenuItem $menuItem) use ($parent) {
            $menuItem->parent()->associate($parent);
        });
    }
}
