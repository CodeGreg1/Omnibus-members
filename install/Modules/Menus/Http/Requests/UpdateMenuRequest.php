<?php

namespace Modules\Menus\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Menus\Rules\RequiredEnglishRule;

class UpdateMenuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:menus,name,'.request()->route('id'),
            'type' => 'required',
            'menu-edit-output' => [
                new RequiredEnglishRule()
            ]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.menus.update');
    }
}
