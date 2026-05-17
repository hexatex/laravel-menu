<?php

namespace Hexatex\LaravelMenu\Menu\Requests\Admin;

use Hexatex\LaravelMenu\MenuItem\Requests\Admin\MenuItemRequest;
use Hexatex\LaravelMisc\FormRequest;

class MenuRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'display_name' => ['required', 'string', 'max:191'],
        ];

        for ($i = 1; $i <= config('menu.max-menu-depth'); $i++) {
            $namespace = str_repeat('menuItems.*.', $i);

            foreach ((new MenuItemRequest)->rules($namespace) as $key => $rule) {
                $rules[$namespace . $key] = $rule;
            }
        }

        return $rules;
    }
}
