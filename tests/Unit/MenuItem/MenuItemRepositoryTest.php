<?php

namespace Hexatex\LaravelMenu\Tests\Unit\MenuItem;

use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\MenuItem\Builders\BuilderFactory;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\MenuItem as ConcreteMenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMenu\MenuItem\MenuItemRepository;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator as ConcreteLengthAwarePaginator;
use Illuminate\Support\Collection;
use \Mockery;

class MenuItemRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $menuRepository;
    protected $builderFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->builderFactory = $this->mock(BuilderFactory::class);
        $this->menuRepository = $this->getMockBuilder(MenuItemRepository::class)
            ->onlyMethods(['getModel', 'create'])
            ->setConstructorArgs([$this->builderFactory])
            ->getMock();
    }

    public function testStore()
    {
        // Arrange
        $fill = $this->menuFill();
        $menu = $this->mockMenuItem();
        $builder = $this->mockMenuItemBuilder();
        $menuItemParent = Mockery::mock(MenuItemParent::class);

        // Act
        $this->menuRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn($menu);

        $this->builderFactory
            ->shouldReceive('create')
            ->once()
            ->with($fill, $menu)
            ->andReturn($builder);

        $builder
            ->shouldReceive('forStore')
            ->once()
            ->with($menuItemParent);

        $menu
            ->shouldReceive('save')
            ->once()
            ->with();

        $result = $this->menuRepository->store($fill, $menuItemParent);

        // Assert
        $this->assertInstanceOf(MenuItem::class, $result);
        $this->assertEquals($menu, $result);
    }

    public function testUpdate()
    {
        // Arrange
        $fill = $this->menuFill();
        $menu = $this->mockMenuItem();
        $builder = $this->mockMenuItemBuilder();
        $menuItemParent = Mockery::mock(MenuItemParent::class);

        // Act
        $this->builderFactory
            ->shouldReceive('create')
            ->once()
            ->with($fill, $menu)
            ->andReturn($builder);

        $builder
            ->shouldReceive('forUpdate')
            ->once()
            ->with($menuItemParent);

        $menu
            ->shouldReceive('save')
            ->once()
            ->andReturnNull();

        $this->menuRepository->update($fill, $menu, $menuItemParent);

        // Assert
        $this->addToAssertionCount(1);
    }

    public function testDestroy()
    {
        // Arrange
        $menu = $this->mockMenuItem();

        // Act
        $menu
            ->shouldReceive('delete')
            ->once();

        $result = $this->menuRepository->destroy($menu);

        // Assert
        $this->assertNull($result);
    }

    public function testGetModel()
    {
        // Arrange
        $repository = new MenuItemRepository($this->builderFactory);
        $method = $this->getInaccessibleMethod($repository, 'getModel');

        // Act
        $result = $method->invoke($repository);

        // Assert
        $this->assertEquals(ConcreteMenuItem::class, $result);
    }

    public function testCreate()
    {
        // Arrange
        $repository = $this->getMockBuilder(MenuItemRepository::class)
            ->onlyMethods(['getModel'])
            ->setConstructorArgs([$this->builderFactory])
            ->getMock();

        $method = $this->getInaccessibleMethod($repository, 'create');

        // Act
        $repository
            ->expects($this->once())
            ->method('getModel')
            ->willReturn(ConcreteMenuItem::class);

        $result = $method->invoke($repository);

        // Assert
        $this->assertInstanceOf(MenuItem::class, $result);
    }

    /*
     * Protected Methods
     */
    protected function mockMenuItem()
    {
        return $this->mock(MenuItem::class);
    }

    protected function mockMenuItemBuilder()
    {
        return $this->mock(\Hexatex\LaravelMenu\MenuItem\Builders\Builder::class);
    }

    protected function menuFill()
    {
        return MenuItemFactory::new()->make()->toArray();
    }
}
