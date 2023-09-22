<?php

namespace Modules\Categories\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Categories\Rules\CategoryUniqueRule;

class AdminStoreCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [  
            'category_type_id' => 'required',     
            'name' => ['required', new CategoryUniqueRule()],            
            'description' => 'nullable',            
            'color' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.categories.store');
    }
}
