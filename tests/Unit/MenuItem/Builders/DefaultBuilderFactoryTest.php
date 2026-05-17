<?php

namespace Hexatex\LaravelMenu\Tests\Unit\MenuItem\Builders;

use Hexatex\LaravelMenu\MenuItem\Builders\DefaultBuilder;
use Hexatex\LaravelMenu\MenuItem\Builders\DefaultBuilderFactory;
use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DefaultBuilderFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        // Arrange
        $fill = MenuItemFactory::new()->make()->toArray();
        $menuItem = MenuItemFactory::new()->make();

        $builderFactory = new DefaultBuilderFactory();

        // Act
        $builder = $builderFactory->create($fill, $menuItem);

        // Assert
        $this->assertInstanceOf(DefaultBuilder::class, $builder);
    }
}
