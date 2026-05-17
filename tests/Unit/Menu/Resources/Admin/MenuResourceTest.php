<?php

namespace Hexatex\LaravelMenu\Tests\Unit\Menu\Resources\Admin;

use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\MenuItem\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Resources\Admin\MenuItemResource;
use Hexatex\LaravelMenu\Menu\Menu;
use Hexatex\LaravelMenu\Menu\Resources\Admin\MenuResource;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MenuResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testMenuResource()
    {
        // Arrange
        $menu = MenuFactory::new()->make();
        $menu->id = 12345;
        $menu->created_at = Carbon::yesterday();
        $menu->updated_at = Carbon::now();
        $resource = new MenuResource($menu);

        $menuItem = MenuItemFactory::new()->make();
        $menuItem->id = 54321;
        $menuItem->created_at = Carbon::yesterday();
        $menuItem->updated_at = Carbon::now();

        $menuItems = new Collection($menuItem);

        $menu->setRelation('menuItems', $menuItems);

        // Act
        $data = $resource->toArray(request());

        // Assert
        $this->assertEquals([
            'id' => $menu->id,
            'display_name' => $menu->display_name,
            'code' => $menu->code,
            'created_at' => $menu->created_at,
            'updated_at' => $menu->updated_at,
            'menuItems' => MenuItemResource::collection($menuItems),
        ], $data);
    }
}
