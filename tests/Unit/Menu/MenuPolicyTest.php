<?php

namespace Hexatex\LaravelMenu\Tests\Unit\Menu;

use Hexatex\LaravelMenu\Factories\MenuFactory;
use Hexatex\LaravelMenu\Menu\MenuPolicy;
use Hexatex\LaravelMenu\Tests\TestCase;
use Hexatex\LaravelUser\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Mockery;

class MenuPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $noCodeMenu;
    protected $codeMenu;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->make();
        $this->user = Mockery::mock($this->user)->makePartial();
        $this->user->id = 1000;

        $this->noCodeMenu = MenuFactory::new()->make();
        $this->noCodeMenu->code = null;
        $this->noCodeMenu = Mockery::mock($this->noCodeMenu)->makePartial();

        $this->codeMenu = MenuFactory::new()->make();
        $this->codeMenu->code = 'test-code';
        $this->codeMenu = Mockery::mock($this->codeMenu)->makePartial();
    }

    public function testView()
    {
        // Arrange
        $policy = new MenuPolicy($this->user, $this->codeMenu);

        // Act
        $this->shouldAllowPermissionTo('menu.view', true);

        $result = $policy->view();

        // Assert
        $this->assertTrue($result);
    }

    public function testStore()
    {
        // Arrange
        $policy = new MenuPolicy($this->user, $this->codeMenu);

        // Act
        $this->shouldAllowPermissionTo('menu.store', true);

        $result = $policy->store();

        // Assert
        $this->assertTrue($result);
    }

    public function testUpdate()
    {
        // Arrange
        $policy = new MenuPolicy($this->user, $this->codeMenu);

        // Act
        $this->shouldAllowPermissionTo('menu.update', true);

        $result = $policy->update();

        // Assert
        $this->assertTrue($result);
    }

    public function testDestroy(bool $hasCode = false)
    {
        // Arrange
        $menu = $hasCode ? $this->codeMenu : $this->noCodeMenu;
        $policy = new MenuPolicy($this->user, $menu);

        // Act
        if (!$hasCode) {
            $this->shouldAllowPermissionTo('menu.destroy', true);
        }

        $result = $policy->destroy();

        // Assert
        if ($hasCode) {
            $this->assertFalse($result);
        } else {
            $this->assertTrue($result);
        }
    }

    public function testDestroy_noCode()
    {
        $this->testDestroy(hasCode: true);
    }

    public function testNoUser()
    {
        // Arrange
        $policy = new MenuPolicy(null, $this->noCodeMenu);

        // Act
        $result = $policy->update();

        // Assert
        $this->assertFalse($result);
    }

    protected function shouldAllowPermissionTo(string $permission, bool $allow)
    {
        $this->user
            ->shouldReceive('hasPermissionTo')
            ->with($permission)
            ->andReturn($allow);
    }
}
