<?php

namespace Hexatex\LaravelMenu\Menu\Resources\Admin;

use Hexatex\LaravelMenu\MenuItem\Resources\Admin\MenuItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'code' => $this->code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            /* MenuItemParent */
            'menuItems' => MenuItemResource::collection($this->whenLoaded('menuItems')),
        ];
    }
}
