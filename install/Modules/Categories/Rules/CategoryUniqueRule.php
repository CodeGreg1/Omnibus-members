<?php

namespace Modules\Categories\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Categories\Repositories\CategoriesRepository;

class CategoryUniqueRule implements Rule
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
        $categories = new CategoriesRepository;

        $category = $categories
            ->where('name', $value)
            ->where('category_type_id', request('category_type_id'));

        if(request()->route('id') != '') {
            $category = $category->whereNot('id', request()->route('id'));
        }

        $category = $category->first();

        return is_null($category);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The category has already been taken.');
    }
}
