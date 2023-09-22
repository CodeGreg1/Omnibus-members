<?php

namespace Modules\CategoryTypes\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\CategoryTypes\Repositories\CategoryTypesRepository;

class CategoryTypeUniqueRule implements Rule
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
        $categoryTypes = new CategoryTypesRepository;

        $categoryType = $categoryTypes
            ->where('name', $value)
            ->where('type', request('type'));

        if(request()->route('id') != '') {
            $categoryType = $categoryType->whereNot('id', request()->route('id'));
        }

        $categoryType = $categoryType->first();

        return is_null($categoryType);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The category type has already been taken.');
    }
}
