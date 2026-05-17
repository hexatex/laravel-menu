<?php

namespace Hexatex\LaravelMenu\Tests\Unit\Menu\Builders;

use Hexatex\LaravelMenu\Menu\Builders\DefaultBuilder;
use Hexatex\LaravelMenu\Menu\Builders\DefaultBuilderFactory;
use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DefaultBuilderFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        // Arrange
        $fill = MenuFactory::new()->make()->toArray();
        $menu = MenuFactory::new()->make();

        $builderFactory = new DefaultBuilderFactory();

        // Act
        $resizer = $builderFactory->create($fill, $menu);

        // Assert
        $this->assertInstanceOf(DefaultBuilder::class, $resizer);
    }
}
