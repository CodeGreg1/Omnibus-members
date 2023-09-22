<?php

namespace Modules\CategoryTypes\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\CategoryTypes\Rules\CategoryTypeUniqueRule;

class AdminUpdateCategoryTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required',            
            'name' => ['required', new CategoryTypeUniqueRule()],            
            'description' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.category-types.update');
    }
}
