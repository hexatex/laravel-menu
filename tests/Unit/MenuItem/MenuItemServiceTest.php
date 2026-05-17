<?php

namespace Hexatex\LaravelMenu\Tests\Unit\MenuItem;

use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\MenuItemItem\Contracts\MenuItemItemService;
use Hexatex\LaravelMenu\MenuItem\Builders\BuilderFactory;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemRepository;
use Hexatex\LaravelMenu\MenuItem\MenuItemService;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use \Mockery;

class MenuItemServiceTest extends TestCase
{
    protected $menuItemRepository;
    protected $menuItemService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->menuItemRepository = Mockery::mock(MenuItemRepository::class);

        $this->menuItemService = $this->getMockBuilder(MenuItemService::class)
            ->onlyMethods(['storeUpdateDestroyMany'])
            ->setConstructorArgs([$this->menuItemRepository])
            ->getMock();
    }

    public function testStore()
    {
        // Arrange
        $fill = [
            'display_name' => 'test display_name',
            'menuItems' => [['test' => 'menuItem-item-1']],
        ];
        $menuItem = $this->mockMenuItem();
        $menuItemParent = Mockery::mock(MenuItemParent::class);

        // Act
        $this->menuItemRepository
            ->shouldReceive('store')
            ->once()
            ->with($fill, $menuItemParent)
            ->andReturn($menuItem);

        $this->menuItemService
            ->expects($this->once())
            ->method('storeUpdateDestroyMany')
            ->with(
                $fill['menuItems'],
                $menuItem->menuItems,
                $this->callback(function ($store) {
                    return is_callable($store);
                }),
                $this->callback(function ($update) {
                    return is_callable($update);
                }),
                $this->callback(function ($destroy) {
                    return is_callable($destroy);
                }),
            );

        $result = $this->menuItemService->store($fill, $menuItemParent);

        // Assert
        $this->assertInstanceOf(MenuItem::class, $result);
        $this->assertEquals($menuItem, $result);
    }

    public function testUpdate()
    {
        // Arrange
        $fill = [
            'display_name' => 'test display_name',
            'menuItems' => [['test' => 'menuItem-item-1']],
        ];
        $menuItem = $this->mockMenuItem();
        $menuItemParent = Mockery::mock(MenuItemParent::class);

        // Act
        $this->menuItemRepository
            ->shouldReceive('update')
            ->once()
            ->with($fill, $menuItem, $menuItemParent);

        $this->menuItemService
            ->expects($this->once())
            ->method('storeUpdateDestroyMany')
            ->with(
                $fill['menuItems'],
                $menuItem->menuItems,
                $this->callback(function ($store) {
                    return is_callable($store);
                }),
                $this->callback(function ($update) {
                    return is_callable($update);
                }),
                $this->callback(function ($destroy) {
                    return is_callable($destroy);
                }),
            );

        $this->menuItemService->update($fill, $menuItem, $menuItemParent);

        // Assert
        $this->addToAssertionCount(1);
    }

    public function testDestroy()
    {
        // Arrange
        $menuItem = $this->mockMenuItem();

        $this->menuItemRepository
            ->shouldReceive('destroy')
            ->once()
            ->with($menuItem);

        // Act
        $this->menuItemService->destroy($menuItem);

        // Assert
        $this->addToAssertionCount(1);
    }

    protected function mockMenuItem()
    {
        $menuItems = MenuItemFactory::new()->count(2)->make();

        $menuItem = MenuItemFactory::new()->make();
        $menuItem->setRelation('menuItems', $menuItems);

        return Mockery::mock($menuItem);
    }
}
