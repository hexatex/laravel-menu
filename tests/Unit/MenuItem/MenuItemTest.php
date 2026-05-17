<?php

namespace Hexatex\LaravelMenu\Tests\Unit\MenuItem;

use Hexatex\LaravelImage\Image\Image;
use Hexatex\LaravelMenu\Factories\MenuItemFactory;
use Hexatex\LaravelMenu\MenuItem\MenuItem;
use Hexatex\LaravelMenu\MenuItemgedItem\MenuItemgedItem;
use Hexatex\LaravelMenu\Tests\TestCase;
use Hexatex\LaravelMenu\Tests\TestObjects\TestMenuItemable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use \Mockery;

class MenuItemTest extends TestCase
{
    use RefreshDatabase;

    public function testMenuItemTableExists()
    {
        // Act & Assert
        $this->assertTrue(Schema::hasTable('menu_items'));
    }

    public function testMenuItemTableExistsWithCorrectColumns()
    {
        // Act
        $tableColumns = Schema::getColumnListing('menu_items');

        // Assert
        $expectedColumns = ['id', 'display_name', 'created_at', 'updated_at', 'parent_type', 'parent_id', 'menu_itemable_id', 'menu_itemable_type'];
        foreach ($expectedColumns as $column) {
            $this->assertContains($column, $tableColumns);
        }
    }

    public function testFillableAttributes()
    {
        // Arrange
        $menuItem = new MenuItem();

        // Act
        $fillableAttributes = $menuItem->getFillable();

        // Assert
        $expectedFillable = ['display_name'];
        $this->assertEquals($expectedFillable, $fillableAttributes);
    }

    public function testMenuItemItemsRelation()
    {
        // Arrange
        $menuItem = new MenuItem();

        // Act
        $relation = $menuItem->menuItems();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphMany::class, $relation);
        $this->assertInstanceOf(MenuItem::class, $relation->getRelated());
    }

    public function testParentRelation()
    {
        // Arrange
        $menuItem = new MenuItem();

        // Act
        $relation = $menuItem->parent();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphTo::class, $relation);
    }

    public function testMenuItemableRelation()
    {
        // Arrange
        $menuItem = new MenuItem();

        // Act
        $relation = $menuItem->menuItemable();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphTo::class, $relation);
    }

    public function testCreateMenuItem()
    {
        // Arrange
        $fill = [
            'display_name' => 'test-menu-item display_name',
        ];
        $menuItem = $this->makeMenuItem($fill);

        // Act
        $menuItem->save();

        // Assert
        $this->assertNotNull($menuItem);
        $this->assertInstanceOf(MenuItem::class, $menuItem);
        $this->assertEquals('test-menu-item display_name', $menuItem->display_name);
        $this->assertDatabaseHas('menu_items', ['id' => $menuItem->id]);
    }

    public function testUpdateMenuItem()
    {
        // Arrange
        $fill =[
            'display_name' => 'test-menu-item display_name',
        ];
        $menuItem = $this->makeMenuItem($fill);
        $menuItem->save();

        $updatedData = [
            'display_name' => 'updated-menu-item display_name',
        ];

        // Act
        $menuItem->update($updatedData);

        // Assert
        $this->assertEquals('updated-menu-item display_name', $menuItem->fresh()->display_name);
        $this->assertDatabaseHas('menu_items', ['id' => $menuItem->id]);
    }

    public function testDeleteMenuItem()
    {
        // Arrange
        $fill = [
            'display_name' => 'menu-item-to-be-deleted display_name',
        ];
        $menuItem = $this->makeMenuItem($fill);
        $menuItem->save();

        // Act
        $menuItem->delete();

        // Assert
        $this->assertDatabaseMissing('menu_items', ['id' => $menuItem->id]);
    }

    /*
     * Protected Methods
     */
    protected function makeMenuItem(array $fill = [])
    {
        $testMenuItemable = new TestMenuItemable;
        $testMenuItemable->id = 12345;
        $menuItem = new MenuItem($fill);
        $menuItem->menuItemable()->associate($testMenuItemable);

        return $menuItem;
    }
}
