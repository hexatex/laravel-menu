<?php

namespace Hexatex\LaravelMenu\Tests;

use Hexatex\LaravelMenu\AdminMenuItemableResources;
use Hexatex\LaravelMenu\LaravelMenuServiceProvider;
use Hexatex\LaravelMenu\MenuItemableModels;
use Hexatex\LaravelMenu\Tests\TestObjects\TestMenuItemable;
use Hexatex\LaravelMenu\Tests\TestObjects\TestMenuItemableResource;
use Hexatex\LaravelMenu\WebMenuItemableResources;
use Hexatex\LaravelSlug\LaravelSlugServiceProvider;
use Hexatex\LaravelUser\LaravelUserServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Intervention\Menu\MenuServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use \ReflectionClass;

abstract class TestCase extends Orchestra
{
    use DatabaseMigrations;

    protected function getPackageProviders($app)
    {
        return [
            LaravelMenuServiceProvider::class,
            LaravelSlugServiceProvider::class,
            LaravelUserServiceProvider::class,
            \Laravel\Passport\PassportServiceProvider::class,
            \Spatie\Permission\PermissionServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:jqAF7i3K4aZ+6h/jN/jl9ZQRCn1c3LPGACxe+x7FpBQ=');
    }

    protected function getInaccessibleMethod($class, string $methodName)
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    protected function setUp(): void
    {
        parent::setUp();

        Relation::enforceMorphMap(['test-menu-itemable' => TestMenuItemable::class]);

        WebMenuItemableResources::register(TestMenuItemable::class, TestMenuItemableResource::class);
        AdminMenuItemableResources::register(TestMenuItemable::class, TestMenuItemableResource::class);
        MenuItemableModels::register(TestMenuItemable::class);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
