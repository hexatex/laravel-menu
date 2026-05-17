<?php

namespace Hexatex\LaravelMenu\Factories;

use Hexatex\LaravelMenu\Tests\TestObjects\TestMenuItemable;
use Hexatex\LaravelSlug\Slug\Contracts\Slug;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestMenuItemableFactory extends Factory
{
    protected $model = TestMenuItemable::class;

    public function definition()
    {
        return [];
    }
}
