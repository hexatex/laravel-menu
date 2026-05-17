<?php

namespace Hexatex\LaravelMenu\Tests\Feature\Menu;

use Hexatex\LaravelImage\Factories\ImageFactory;
use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\Factories\TestMenuItemableFactory;
use Hexatex\LaravelMenu\Menu\Menu;
use Hexatex\LaravelMenu\Menu\Resources\Admin\MenuResource;
use Hexatex\LaravelMenu\Tests\FeatureTestCase;
use Hexatex\LaravelSlug\Factories\SlugFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class MenuAdminApiTest extends FeatureTestCase
{
    use RefreshDatabase;

    public function testGetMenu()
    {
        $menu = MenuFactory::new()->create();
        $menuItemable1 = TestMenuItemableFactory::new()->create();
        $menuItem1 = MenuItemFactory::new()->menuItemable($menuItemable1)->parent($menu)->create();
        $slug1 = SlugFactory::new()->sluggable($menuItemable1)->create();

        $menuItemable2 = TestMenuItemableFactory::new()->create();
        $menuItem2 = MenuItemFactory::new()->menuItemable($menuItemable2)->parent($menuItem1)->create();
        $slug2 = SlugFactory::new()->sluggable($menuItemable2)->create();

        $menuItemable3 = TestMenuItemableFactory::new()->create();
        $menuItem3 = MenuItemFactory::new()->menuItemable($menuItemable3)->parent($menuItem2)->create();
        $slug3 = SlugFactory::new()->sluggable($menuItemable3)->create();

        $response = $this->getJson("admin/api/menu/{$menu->id}");

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'display_name',
                    'code',
                    'created_at',
                    'updated_at',
                    'menuItems' => [
                        [
                            'id',
                            'display_name',
                            'created_at',
                            'updated_at',
                            'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                            'menuItems' => [
                                [
                                    'id',
                                    'display_name',
                                    'created_at',
                                    'updated_at',
                                    'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                                    'menuItems' => [
                                        [
                                            'id',
                                            'display_name',
                                            'created_at',
                                            'updated_at',
                                            'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                                            'menuItems' => [],
                                        ]

                                    ],
                                ]
                            ],
                        ]
                    ],
                ],
            ])->assertJsonFragment([
                'id' => $menu->id,
                'display_name' => $menu->display_name,
                'code' => $menu->code,
                'created_at' => $menu->created_at,
                'updated_at' => $menu->updated_at,
            ])->assertJsonFragment([
                'id' => $menuItem1->id,
                'display_name' => $menuItem1->display_name,
                'created_at' => $menuItem1->created_at,
                'updated_at' => $menuItem1->updated_at,
            ])->assertJsonFragment([
                'id' => $menuItem2->id,
                'display_name' => $menuItem2->display_name,
                'created_at' => $menuItem2->created_at,
                'updated_at' => $menuItem2->updated_at,
            ])->assertJsonFragment([
                'id' => $menuItem3->id,
                'display_name' => $menuItem3->display_name,
                'created_at' => $menuItem3->created_at,
                'updated_at' => $menuItem3->updated_at,
            ])->assertJsonFragment([
                'id' => $menuItemable1->id,
                'morph_class' => $menuItemable1->getMorphClass(),
            ])->assertJsonFragment([
                'id' => $menuItemable2->id,
                'morph_class' => $menuItemable2->getMorphClass(),
            ])->assertJsonFragment([
                'id' => $menuItemable3->id,
                'morph_class' => $menuItemable3->getMorphClass(),
            ])->assertJsonFragment([
                'slug' => $slug1->slug,
            ])->assertJsonFragment([
                'slug' => $slug2->slug,
            ])->assertJsonFragment([
                'slug' => $slug3->slug,
            ]);
    }

    public function testStoreMenu()
    {
        $fillMenu = MenuFactory::new()->make();

        $menuItemable1 = TestMenuItemableFactory::new()->create();
        $fillMenuItem1 = MenuItemFactory::new()->menuItemable($menuItemable1)->make();
        $slug1 = SlugFactory::new()->sluggable($menuItemable1)->create();

        $fillMenu->setRelation('menuItems', collect([$fillMenuItem1]));

        $menuItemable2 = TestMenuItemableFactory::new()->create();
        $fillMenuItem2 = MenuItemFactory::new()->menuItemable($menuItemable2)->make();
        $slug2 = SlugFactory::new()->sluggable($menuItemable2)->create();

        $fillMenuItem1->setRelation('menuItems', collect([$fillMenuItem2]));

        $menuItemable3 = TestMenuItemableFactory::new()->create();
        $fillMenuItem3 = MenuItemFactory::new()->menuItemable($menuItemable3)->make();
        $slug3 = SlugFactory::new()->sluggable($menuItemable3)->create();

        $fillMenuItem2->setRelation('menuItems', collect([$fillMenuItem3]));

        $fill = $this->resourceToArray(new MenuResource($fillMenu));

        $response = $this->postJson("admin/api/menu", $fill);

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'display_name',
                    'code',
                    'created_at',
                    'updated_at',
                    'menuItems' => [
                        [
                            'id',
                            'display_name',
                            'created_at',
                            'updated_at',
                            'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                            'menuItems' => [
                                [
                                    'id',
                                    'display_name',
                                    'created_at',
                                    'updated_at',
                                    'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                                    'menuItems' => [
                                        [
                                            'id',
                                            'display_name',
                                            'created_at',
                                            'updated_at',
                                            'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                                            'menuItems' => [],
                                        ]

                                    ],
                                ]
                            ],
                        ]
                    ],
                ],
            ])->assertJsonFragment([
                'display_name' => $fillMenu->display_name,
                'code' => null, // You cannot set code from the ui
            ])->assertJsonFragment([
                'display_name' => $fillMenuItem1->display_name,
            ])->assertJsonFragment([
                'display_name' => $fillMenuItem2->display_name,
            ])->assertJsonFragment([
                'display_name' => $fillMenuItem3->display_name,
            ])->assertJsonFragment([
                'id' => $menuItemable1->id,
                'morph_class' => $menuItemable1->getMorphClass(),
            ])->assertJsonFragment([
                'id' => $menuItemable2->id,
                'morph_class' => $menuItemable2->getMorphClass(),
            ])->assertJsonFragment([
                'id' => $menuItemable3->id,
                'morph_class' => $menuItemable3->getMorphClass(),
            ])->assertJsonFragment([
                'slug' => $slug1->slug,
            ])->assertJsonFragment([
                'slug' => $slug2->slug,
            ])->assertJsonFragment([
                'slug' => $slug3->slug,
            ]);
    }

    public function testUpdateMenu()
    {
        $menu = MenuFactory::new()->create();
        $menuFill = MenuFactory::new()->make();

        $menuItemableUpdate = TestMenuItemableFactory::new()->create();
        $slugUpdate = SlugFactory::new()->sluggable($menuItemableUpdate)->create();
        $menuItemUpdate = MenuItemFactory::new()->parent($menu)->menuItemable($menuItemableUpdate)->create();
        $menuItemUpdateFill = MenuItemFactory::new()->menuItemable($menuItemableUpdate)->make();
        $menuItemUpdateFill->id = $menuItemUpdate->id;
        $menuFill->setRelation('menuItems', collect([$menuItemUpdateFill]));

        $menuItemableStore = TestMenuItemableFactory::new()->create();
        $slugStore = SlugFactory::new()->sluggable($menuItemableStore)->create();
        $menuItemStoreFill = MenuItemFactory::new()->menuItemable($menuItemableStore)->make();
        $menuItemUpdateFill->setRelation('menuItems', collect([$menuItemStoreFill]));

        $menuItemableDestroy1 = TestMenuItemableFactory::new()->create();
        $slugDestroy1 = SlugFactory::new()->sluggable($menuItemableDestroy1)->create();
        $menuItemDestroy1 = MenuItemFactory::new()->parent($menu)->menuItemable($menuItemableDestroy1)->create();

        $menuItemableDestroy2 = TestMenuItemableFactory::new()->create();
        $slugDestroy2 = SlugFactory::new()->sluggable($menuItemableDestroy2)->create();
        $menuItemDestroy2 = MenuItemFactory::new()->parent($menuItemDestroy1)->menuItemable($menuItemableDestroy2)->create();

        $fill = $this->resourceToArray(new MenuResource($menuFill));

        $response = $this->putJson("admin/api/menu/{$menu->id}", $fill);

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'display_name',
                    'code',
                    'created_at',
                    'updated_at',
                    'menuItems' => [
                        [
                            'id', // menuItemUpdate
                            'display_name',
                            'created_at',
                            'updated_at',
                            'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                            'menuItems' => [
                                [
                                    'id', // menuItemStore
                                    'display_name',
                                    'created_at',
                                    'updated_at',
                                    'menuItemable' => ['id', 'morph_class', 'slug' => ['slug']],
                                    'menuItems' => [],
                                ]
                            ],
                        ]
                    ],
                ],
            ])->assertJsonFragment([
                'display_name' => $menuFill->display_name,
                'code' => $menu->code, // You cannot set code from the ui
            ])->assertJsonFragment([
                'display_name' => $menuItemUpdateFill->display_name,
            ])->assertJsonFragment([
                'display_name' => $menuItemStoreFill->display_name,
            ])->assertJsonFragment([
                'id' => $menuItemableUpdate->id,
                'morph_class' => $menuItemableUpdate->getMorphClass(),
            ])->assertJsonFragment([
                'id' => $menuItemableStore->id,
                'morph_class' => $menuItemableStore->getMorphClass(),
            ])->assertJsonFragment([
                'slug' => $slugUpdate->slug,
            ])->assertJsonFragment([
                'slug' => $slugStore->slug,
            ]);

        $this->assertDatabaseMissing('menu_items', ['id' => $menuItemDestroy1->id]);
        $this->assertDatabaseMissing('menu_items', ['id' => $menuItemDestroy2->id]);
    }

    public function testDestroyMenu()
    {
        $menu = MenuFactory::new()->create();
        $menuFill = MenuFactory::new()->make();

        $menuItemable = TestMenuItemableFactory::new()->create();
        $slug = SlugFactory::new()->sluggable($menuItemable)->create();
        $menuItem = MenuItemFactory::new()->parent($menu)->menuItemable($menuItemable)->create();

        $response = $this->deleteJson("admin/api/menu/{$menu->id}");

        $response->assertSuccessful()->assertJsonStructure([]);

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
        $this->assertDatabaseMissing('menu_items', ['id' => $menuItem->id]);
    }
}
