<?php

namespace Hexatex\LaravelMenu\Tests\Unit\Menu\Builders;

use Hexatex\LaravelMenu\Menu\Builders\DefaultBuilder;
use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Mockery;

class DefaultBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function testForStore()
    {
        // Arrange
        $fill = [];
        $menu = Mockery::mock(Menu::class);
        $builder = new DefaultBuilder($fill, $menu);

        // Act
        $menu
            ->shouldReceive('fill')
            ->with($fill)
            ->once();

        $builder->forStore();

        // Assert
        $this->addToAssertionCount(1);
    }

    public function testForUpdate()
    {
        // Arrange
        $fill = [];
        $menu = Mockery::mock(Menu::class);
        $builder = new DefaultBuilder($fill, $menu);

        // Act
        $menu
            ->shouldReceive('fill')
            ->with($fill)
            ->once();

        $builder->forUpdate();

        // Assert
        $this->addToAssertionCount(1);
    }
}
