<?php

namespace Hexatex\LaravelMenu\Http\Controllers\Admin\Api;

use Hexatex\LaravelMenu\MenuItemableModels;
use Hexatex\LaravelMenu\Menu\Contracts\Menu;
use Hexatex\LaravelMenu\Menu\Contracts\MenuService;
use Hexatex\LaravelMenu\Menu\Requests\Admin\IndexRequest;
use Hexatex\LaravelMenu\Menu\Requests\Admin\MenuRequest;
use Hexatex\LaravelMenu\Menu\Resources\Admin\MenuResource;
use Hexatex\LaravelMisc\Contracts\Model;
use Hexatex\LaravelMisc\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use \Auth;

class MenuController extends Controller
{
    public function __construct(MenuService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $menus = $this->service->index($request->validated());

        return MenuResource::collection($menus);
    }

    public function get(Menu $menu): MenuResource
    {
        $this->load($menu);

        return new MenuResource($menu);
    }

    public function store(MenuRequest $request): MenuResource
    {
        $this->db->transaction(function () use ($request, &$menu) {
            $menu = $this->service->store($request->validated(), Auth::user());
        });

        $this->load($menu);

        return new MenuResource($menu);
    }

    public function update(MenuRequest $request, Menu $menu): MenuResource
    {
        $this->db->transaction(function () use ($request, $menu) {
            $this->service->update($request->validated(), $menu);
        });

        $this->load($menu);

        return new MenuResource($menu);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        $this->db->transaction(function () use ($menu) {
            $this->service->destroy($menu);
        });

        return response()->json();
    }

    /*
     * Protected Methods
     */
    protected function load(Model $model): void
    {
        $maxMenuDepth = config('menu.max-menu-depth');

        $load = str_repeat('menuItems.', $maxMenuDepth);
        $loading = [rtrim($load, '.')];

        for ($i = 1; $i <= $maxMenuDepth; $i++) {
            $loading[] = str_repeat('menuItems.', $i) . 'menuItemable.slug';
        }

        $model->load($loading);
    }
}
