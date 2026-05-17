<?php

namespace Hexatex\LaravelMenu\Tests\Unit\Menu;

use Hexatex\LaravelMenu\Menu\Builders\BuilderFactory;
use Hexatex\LaravelMenu\Menu\MenuRepository;
use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMenu\Menu\Menu as ConcreteMenu;
use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator as ConcreteLengthAwarePaginator;
use Illuminate\Support\Collection;
use \Mockery;

class MenuRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $menuRepository;
    protected $builderFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->builderFactory = $this->mock(BuilderFactory::class);
        $this->menuRepository = $this->getMockBuilder(MenuRepository::class)
            ->onlyMethods(['getModel', 'create'])
            ->setConstructorArgs([$this->builderFactory])
            ->getMock();
    }
    public function testIndex_hasRowsPerPage()
    {
        $filters = [
            'title' => 'Test Title',
            'descending' => true,
            'page' => 1,
            'rowsPerPage' => 10,
            'sortBy' => 'created_at',
        ];

        $this->testIndex($filters);
    }

    public function testIndex_nullRowsPerPage()
    {
        $filters = [
            'title' => 'Test Title',
            'descending' => true,
            'page' => 1,
            'rowsPerPage' => null,
            'sortBy' => 'created_at',
        ];

        $this->testIndex($filters);
    }

    public function testIndex_noRowsPerPage()
    {
        $filters = [
            'title' => 'Test Title',
            'descending' => true,
            'page' => 1,
            'sortBy' => 'created_at',
        ];

        $this->testIndex($filters);
    }

    public function testStore()
    {
        // Arrange
        $fill = $this->menuFill();
        $menu = $this->mockMenu();
        $builder = $this->mockMenuBuilder();

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
            ->with();

        $menu
            ->shouldReceive('save')
            ->once()
            ->with();

        $result = $this->menuRepository->store($fill);

        // Assert
        $this->assertInstanceOf(Menu::class, $result);
        $this->assertEquals($menu, $result);
    }

    public function testUpdate()
    {
        // Arrange
        $fill = $this->menuFill();
        $menu = $this->mockMenu();
        $builder = $this->mockMenuBuilder();

        // Act
        $this->builderFactory
            ->shouldReceive('create')
            ->once()
            ->with($fill, $menu)
            ->andReturn($builder);

        $builder
            ->shouldReceive('forUpdate')
            ->once()
            ->with();

        $menu
            ->shouldReceive('save')
            ->once()
            ->andReturnNull();

        $this->menuRepository->update($fill, $menu);

        // Assert
        $this->addToAssertionCount(1);
    }

    public function testDestroy()
    {
        // Arrange
        $menu = $this->mockMenu();

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
        $repository = new MenuRepository($this->builderFactory);
        $method = $this->getInaccessibleMethod($repository, 'getModel');

        // Act
        $result = $method->invoke($repository);

        // Assert
        $this->assertEquals(ConcreteMenu::class, $result);
    }

    public function testCreate()
    {
        // Arrange
        $repository = $this->getMockBuilder(MenuRepository::class)
            ->onlyMethods(['getModel'])
            ->setConstructorArgs([$this->builderFactory])
            ->getMock();

        $method = $this->getInaccessibleMethod($repository, 'create');

        // Act
        $repository
            ->expects($this->once())
            ->method('getModel')
            ->willReturn(ConcreteMenu::class);

        $result = $method->invoke($repository);

        // Assert
        $this->assertInstanceOf(Menu::class, $result);
    }

    /*
     * Protected Methods
     */
    protected function testIndex(array $filters)
    {
        // Arrange
        $menu = $this->mockMenu();
        $queryBuilder = $this->mock(\Illuminate\Database\Eloquent\Builder::class);
        $expectedCollection = new Collection([$menu]);
        $expectedLengthAwarePaginator = new ConcreteLengthAwarePaginator(
            $expectedCollection,
            $expectedCollection->count(),
            $filters['rowsPerPage'] ?? config('menu.default-rows-per-page'),
            $filters['page']
        );

        // Act
        $this->menuRepository
            ->expects($this->once())
            ->method('getModel')
            ->willReturn(get_class($menu));

        $menu
            ->shouldReceive('filter')
            ->once()
            ->with($filters)
            ->andReturn($queryBuilder);

        $queryBuilder
            ->shouldReceive('paginate')
            ->once()
            ->with($filters['rowsPerPage'] ?? config('menu.default-rows-per-page'))
            ->andReturn($expectedLengthAwarePaginator);

        $result = $this->menuRepository->index($filters);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($menu));
        $this->assertEquals($menu, $result->first());
    }

        protected function mockMenu()
    {
        return $this->mock(Menu::class);
    }

    protected function mockMenuBuilder()
    {
        return $this->mock(\Hexatex\LaravelMenu\Menu\Builders\Builder::class);
    }

    protected function menuFill()
    {
        return MenuFactory::new()->make()->toArray();
    }
}
