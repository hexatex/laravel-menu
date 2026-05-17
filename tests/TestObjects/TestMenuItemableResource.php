<?php

namespace Hexatex\LaravelMenu\Tests\TestObjects;

use Hexatex\LaravelSlug\Slug\Resources\Web\SlugResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TestMenuItemableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'morph_class' => $this->getMorphClass(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            /* Sluggable */
            'slug' => new SlugResource($this->whenLoaded('slug')),
        ];
    }
}
