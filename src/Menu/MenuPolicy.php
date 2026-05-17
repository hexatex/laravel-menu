<?php

namespace Hexatex\LaravelMenu\Menu;

use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMisc\Contracts\Authenticatable;

class MenuPolicy
{
    protected $user;
    protected $menu;

    public function __construct(?Authenticatable $user, Menu $menu)
    {
        $this->user = $user;
        $this->menu = $menu;
    }

    public function view(): bool
    {
        return $this->checkPermission('view');
    }

    public function store(): bool
    {
        return $this->checkPermission('store');
    }

    public function update(): bool
    {
        return $this->checkPermission('update');
    }

    public function destroy(): bool
    {
        if ($this->menu->code) {
            return false; // Don't destroy the main menu, others referred to by the software.
        }

        return $this->checkPermission('destroy');
    }

    protected function checkPermission($menuPermission): bool
    {
        if (!$this->user) {
            return false;
        }

        return $this->user->hasPermissionTo("menu.{$menuPermission}");
    }
}
