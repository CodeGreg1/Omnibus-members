<?php

namespace Modules\Modules\Rules;

use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Validation\Rule;
use Modules\Modules\Services\Generators\ModuleGenerator;

class ModuleTableRule implements Rule
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
        $replacements = (new ModuleGenerator(request()->all()))->replacements();

        if(is_null(request('id'))) {
            return Schema::hasTable($replacements['$TABLE_MODEL$']) < 1;
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
        return __('The table name is already exists.');
    }
}
