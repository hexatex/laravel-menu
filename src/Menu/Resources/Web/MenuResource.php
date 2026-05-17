<?php

namespace Hexatex\LaravelMenu\Menu\Resources\Web;

use Hexatex\LaravelMenu\MenuItem\Resources\Web\MenuItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,

            /* MenuItemParent */
            'menuItems' => MenuItemResource::collection($this->whenLoaded('menuItems')),
        ];
    }
}
