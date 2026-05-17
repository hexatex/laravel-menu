<?php

namespace Hexatex\LaravelMenu\Tests\Unit\MenuItem\Resources\Admin;

use Hexatex\LaravelMenu\AdminMenuItemableResources;
use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\Factories\TestMenuItemableFactory;
use Hexatex\LaravelMenu\MenuItemItem\MenuItemItem;
use Hexatex\LaravelMenu\MenuItemItem\Resources\Admin\MenuItemItemResource;
use Hexatex\LaravelMenu\MenuItem\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Resources\Admin\MenuItemResource;
use Hexatex\LaravelMenu\Menu\Resources\Admin\MenuResource;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MenuItemResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testMenuItemResource()
    {
        // Arrange
        $menuItem = MenuItemFactory::new()->make();
        $menuItem->id = 12345;
        $menuItem->display_name = 'Sample display name';
        $menuItem->created_at = Carbon::yesterday();
        $menuItem->updated_at = Carbon::now();
        $resource = new MenuItemResource($menuItem);

        $child = MenuItemFactory::new()->make();
        $child->id = 12345;
        $child->display_name = 'Sample display name';
        $child->created_at = Carbon::yesterday();
        $child->updated_at = Carbon::now();
        $menuItem->setRelation('menuItems', collect([$child]));

        $menuItemable = TestMenuItemableFactory::new()->make();
        $menuItemable->id = 12345;
        $menuItemable->created_at = Carbon::yesterday();
        $menuItemable->updated_at = Carbon::now();
        $menuItem->setRelation('menuItemable', $menuItemable);

        $menu = MenuFactory::new()->make();
        $menu->id = 12345;
        $menu->created_at = Carbon::yesterday();
        $menu->updated_at = Carbon::now();
        $menuItem->setRelation('menu', $menu);

        // Act
        $data = $resource->toArray(request());

        // // Assert
        $this->assertEquals([
            'id' => $menuItem->id,
            'display_name' => $menuItem->display_name,
            'created_at' => $menuItem->created_at,
            'updated_at' => $menuItem->updated_at,

            /* MenuItemParent */
            'menuItems' => MenuItemResource::collection(collect([$child])),

            /* Relationships */
            'menu' => new MenuResource($menu),
            'menuItemable' => AdminMenuItemableResources::single($menuItemable),
        ], $data);
    }
}
