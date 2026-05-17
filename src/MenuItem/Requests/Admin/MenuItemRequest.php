<?php

namespace Hexatex\LaravelMenu\MenuItem\Requests\Admin;

use Hexatex\LaravelMenu\MenuItemableModels;
use Hexatex\LaravelMisc\FormRequest;
use Hexatex\LaravelValidation\Rules\MorphIdExists;
use Hexatex\LaravelValidation\Rules\MorphTypeAllowed;
use Illuminate\Validation\Rule;

class MenuItemRequest extends FormRequest
{
    public function rules(string $namespace = '')
    {
        return [
            'menuItemable.morph_class' => ['required', new MorphTypeAllowed(MenuItemableModels::class)],
            'menuItemable.id' => ['required', new MorphIdExists(MenuItemableModels::class)],
            'display_name' => ['required', 'string', 'max:191'],
        ];
    }
}
