<?php

namespace Modules\Menus\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredEnglishRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $key = 'menu-create-output';

        if(request()->route('id')) {
            $key = 'menu-edit-output';
        }

        $menus = request($key);

        $englishMenu = json_decode($menus['en'], true);

        return isset($menus['en']) && count($englishMenu);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('English menu items is required.');
    }
}
