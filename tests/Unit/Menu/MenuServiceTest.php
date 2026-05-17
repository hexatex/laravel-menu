<?php

namespace Hexatex\LaravelMenu\Tests\Unit\Menu;

use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemService;
use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMenu\Menu\Contracts\MenuRepository;
use Hexatex\LaravelMenu\Menu\MenuService;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use \Mockery;

class MenuServiceTest extends TestCase
{
    protected $menuRepository;
    protected $menuItemService;
    protected $menuService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->menuRepository = $this->mock(MenuRepository::class);

        $this->menuItemService = $this->mock(MenuItemService::class);

        $this->menuService = new MenuService($this->menuRepository, $this->menuItemService);
    }

    public function testIndex()
    {
        // Arrange
        $filters = [
            'descending' => true,
            'page' => 1,
            'rowsPerPage' => 10,
            'sortBy' => 'created_at',
        ];

        $menu = $this->mockMenu();
        $collection = new Collection([$menu]);

        // Act
        $this->menuRepository
            ->shouldReceive('index')
            ->once()
            ->with($filters)
            ->andReturn($collection);

        $result = $this->menuService->index($filters);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($menu));
        $this->assertEquals($menu, $result->first());
    }

    public function testStore()
    {
        // Arrange
        $fill = [
            'display_name' => 'test display_name',
            'menuItems' => [['test' => 'menu-item-1']],
        ];
        $menu = $this->mockMenu();

        // Act
        $this->menuRepository
            ->shouldReceive('store')
            ->once()
            ->with($fill)
            ->andReturn($menu);

        $this->menuItemService
            ->shouldReceive('storeUpdateDestroy')
            ->once()
            ->with($fill['menuItems'], $menu->menuItems, $menu);

        $result = $this->menuService->store($fill);

        // Assert
        $this->assertInstanceOf(Menu::class, $result);
        $this->assertEquals($menu, $result);
    }

    public function testUpdate()
    {
        // Arrange
        $fill = [
            'display_name' => 'test display_name',
            'menuItems' => [['test' => 'menu-item-1']],
        ];
        $menu = $this->mockMenu();

        // Act
        $this->menuRepository
            ->shouldReceive('update')
            ->once()
            ->with($fill, $menu);

        $this->menuItemService
            ->shouldReceive('storeUpdateDestroy')
            ->once()
            ->with($fill['menuItems'], $menu->menuItems, $menu);

        $this->menuService->update($fill, $menu);

        // Assert
        $this->addToAssertionCount(1);
    }

    public function testDestroy()
    {
        // Arrange
        $menu = $this->mockMenu();

        // Act
        $this->menuRepository
            ->shouldReceive('destroy')
            ->once()
            ->with($menu);

        $this->menuItemService
            ->shouldReceive('storeUpdateDestroy')
            ->once()
            ->with([], $menu->menuItems, $menu);

        $this->menuService->destroy($menu);

        // Assert
        $this->addToAssertionCount(1);
    }

    protected function mockMenu()
    {
        $menuItems = MenuItemFactory::new()->count(2)->make();

        $menu = MenuFactory::new()->make();
        $menu->setRelation('menuItems', $menuItems);

        return Mockery::mock($menu);
    }
}
