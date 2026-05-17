<?php

namespace Hexatex\LaravelMenu\Tests\Unit\MenuItem\Builders;

use Hexatex\LaravelMenu\MenuItem\Builders\DefaultBuilder;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItem;
use Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent;
use Hexatex\LaravelMenu\Tests\TestCase;
use Hexatex\LaravelMisc\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Mockery;
use \InvalidArgumentException;

class DefaultBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected $menuItemParent;

    public function setUp(): void
    {
        parent::setUp();

        $this->menuItemParent = Mockery::mock(MenuItemParent::class);
    }

    public function testForStore_noMenuItemable()
    {
        $this->testForStore(
            hasMenuItemable: false,
            hasMenuItemableId: false,
            hasMenuItemableMorphType: false,
        );
    }

    public function testForStore_hasMenuItemable_noId()
    {
        $this->testForStore(
            hasMenuItemable: true,
            hasMenuItemableId: false,
            hasMenuItemableMorphType: true,
        );
    }

    public function testForStore_hasMenuItemable_noMorphType()
    {
        $this->testForStore(
            hasMenuItemable: true,
            hasMenuItemableId: true,
            hasMenuItemableMorphType: false,
        );
    }

    public function testForStore_hasMenuItemable()
    {
        $this->testForStore(
            hasMenuItemable: true,
            hasMenuItemableId: true,
            hasMenuItemableMorphType: true,
        );
    }

    public function testForUpdate()
    {
        // Arrange
        $fill = [];
        $menuItem = Mockery::mock(MenuItem::class);
        $builder = new DefaultBuilder($fill, $menuItem);

        // Arrange and Act
        $this->forStoreUpdateCommon($menuItem, $fill);

        // Act
        $builder->forUpdate($this->menuItemParent);

        // Assert
        $this->addToAssertionCount(1);
    }

    /*
     * Protected Methods
     */
    protected function testForStore(
        bool $hasMenuItemable,
        bool $hasMenuItemableId,
        bool $hasMenuItemableMorphType,
    ) {
        // Arrange
        $fill = [];

        if ($hasMenuItemable) {
            $fill = [
                'menuItemable' => [],
            ];
        }

        if ($hasMenuItemableId) {
            $fill['menuItemable']['id'] = 'test-id';
        }

        if ($hasMenuItemableMorphType) {
            $fill['menuItemable']['morph_class'] = 'test-morph-class';
        }

        $menuItem = Mockery::mock(MenuItem::class);
        $builder = new DefaultBuilder($fill, $menuItem);
        $menuItemableMorphTo = Mockery::mock(MorphTo::class);

        // Arrange and Act
        $this->forStoreUpdateCommon($menuItem, $fill);

        // Act
        if (!$hasMenuItemable || !$hasMenuItemableId || !$hasMenuItemableMorphType) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage('The menuItemable id and morph_class fields cannot be null.');
        } else {
            $menuItem
                ->shouldReceive('menuItemable')
                ->once()
                ->andReturn($menuItemableMorphTo);

            $menuItemableMorphTo
                ->shouldReceive('associateMorph')
                ->with($fill['menuItemable']['id'] ?? null, $fill['menuItemable']['morph_class'] ?? null);
        }

        $builder->forStore($this->menuItemParent);

        // Assert
        if (!$hasMenuItemable || !$hasMenuItemableId || !$hasMenuItemableMorphType) {
            $this->fail('An exception should have been thrown.');
        }

        $this->addToAssertionCount(1);
    }

    protected function forStoreUpdateCommon($menuItem, $fill)
    {
        $menuItemParentMorphTo = Mockery::mock(MorphTo::class);

        $menuItem
            ->shouldReceive('fill')
            ->with($fill)
            ->once();

        $menuItem
            ->shouldReceive('parent')
            ->once()
            ->andReturn($menuItemParentMorphTo);

        $menuItemParentMorphTo
            ->shouldReceive('associate')
            ->once()
            ->with($this->menuItemParent);
    }
}
