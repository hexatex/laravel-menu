<?php

namespace Hexatex\LaravelMenu\Tests\Unit\Menu;

use Hexatex\LaravelImage\Image\Image;
use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\MenuItem\MenuItem;
use Hexatex\LaravelMenu\Menu\Contracts\Menugable;
use Hexatex\LaravelMenu\Menu\Menu;
use Hexatex\LaravelMenu\MenugedItem\MenugedItem;
use Hexatex\LaravelMenu\Tests\TestCase;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use \Mockery;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function testMenuTableExists()
    {
        // Act & Assert
        $this->assertTrue(Schema::hasTable('menus'));
    }

    public function testMenuTableExistsWithCorrectColumns()
    {
        // Act
        $tableColumns = Schema::getColumnListing('menus');

        // Assert
        $expectedColumns = ['id', 'display_name', 'code', 'created_at', 'updated_at'];
        foreach ($expectedColumns as $column) {
            $this->assertContains($column, $tableColumns);
        }
    }

    public function testFillableAttributes()
    {
        // Arrange
        $menu = new Menu();

        // Act
        $fillableAttributes = $menu->getFillable();

        // Assert
        $expectedFillable = ['display_name', 'code'];
        $this->assertEquals($expectedFillable, $fillableAttributes);
    }

    public function testScopeByCode()
    {
        // Arrange
        $menu = new Menu;
        $builder = Mockery::mock(Builder::class);

        // Act
        $builder
            ->shouldReceive('where')
            ->with('code', 'menu-code')
            ->once();

        $menu->scopeByCode($builder, 'menu-code');

        // Assert
        $this->addToAssertionCount(1);
    }

    public function testMenuItemsRelation()
    {
        // Arrange
        $menu = new Menu();

        // Act
        $relation = $menu->menuItems();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphMany::class, $relation);
        $this->assertInstanceOf(MenuItem::class, $relation->getRelated());
    }

    public function testCreateMenu()
    {
        // Arrange
        $fill = [
            'display_name' => 'test-menu display_name',
            'code' => 'test-menu',
        ];
        $menu = $this->makeMenu($fill);

        // Act
        $menu->save();

        // Assert
        $this->assertNotNull($menu);
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals('test-menu display_name', $menu->display_name);
        $this->assertEquals('test-menu', $menu->code);
        $this->assertDatabaseHas('menus', ['id' => $menu->id]);
    }

    public function testUpdateMenu()
    {
        // Arrange
        $fill =[
            'display_name' => 'test-menu display_name',
            'code' => 'test-menu',
        ];
        $menu = $this->makeMenu($fill);
        $menu->save();

        $updatedData = [
            'display_name' => 'updated-menu display_name',
            'code' => 'updated-menu',
        ];

        // Act
        $menu->update($updatedData);

        // Assert
        $this->assertEquals('updated-menu', $menu->fresh()->code);
        $this->assertEquals('updated-menu display_name', $menu->fresh()->display_name);
        $this->assertDatabaseHas('menus', ['id' => $menu->id]);
    }

    public function testDeleteMenu()
    {
        // Arrange
        $fill = [
            'code' => 'menu-to-be-deleted',
            'display_name' => 'menu-to-be-deleted display_name',
        ];
        $menu = $this->makeMenu($fill);
        $menu->save();

        // Act
        $menu->delete();

        // Assert
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    /*
     * Protected Methods
     */
    protected function makeMenu(array $fill = [])
    {
        $menu = new Menu($fill);

        return $menu;
    }
}
