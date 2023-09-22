<?php

namespace Modules\Modules\Rules;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Validation\Rule;

class ModuleExistsRule implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        $file = new Filesystem;

        if(is_null(request('id'))) {
            return !$file->exists(base_path() . '/Modules/'.request('module_name'));
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The module name has already been taken.');
    }
}
