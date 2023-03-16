<?php

namespace Hexatex\LaravelMenu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false; // Used indirectly by related models.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => ['string', 'max:191'],
            'menuItems.*.menu_itemable_type' // Todo: need to allow projects using this package to say what types are allowed and what model/table to check if exists.

            // TODO: !!! Need to change this to use hash id and not id
        ];
    }
}
