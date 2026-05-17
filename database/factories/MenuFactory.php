<?php

namespace Hexatex\LaravelMenu\Factories;

use Hexatex\LaravelMenu\Menu\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'display_name' => $this->faker->sentence,
            'code' => $this->faker->slug,
        ];
    }
}
