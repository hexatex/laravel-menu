<?php

namespace Hexatex\LaravelMenu\MenuItem\Resources\Web;

use Hexatex\LaravelMenu\Menu\Resources\Web\MenuResource;
use Hexatex\LaravelMenu\WebMenuItemableResources;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,

            /* MenuItemParent */
            'menuItems' => MenuItemResource::collection($this->whenLoaded('menuItems')),

            /* Relationships */
            'menu' => new MenuResource($this->whenLoaded('menu')),
            'menuItemable' => WebMenuItemableResources::single($this->whenLoaded('menuItemable')),
        ];
    }
}
