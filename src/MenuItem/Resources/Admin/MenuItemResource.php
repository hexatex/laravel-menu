<?php

namespace Hexatex\LaravelMenu\MenuItem\Resources\Admin;

use Hexatex\LaravelMenu\AdminMenuItemableResources;
use Hexatex\LaravelMenu\Menu\Resources\Admin\MenuResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            /* MenuItemParent */
            'menuItems' => MenuItemResource::collection($this->whenLoaded('menuItems')),

            /* Relationships */
            'menu' => new MenuResource($this->whenLoaded('menu')),
            'menuItemable' => AdminMenuItemableResources::single($this->whenLoaded('menuItemable')),
        ];
    }
}
