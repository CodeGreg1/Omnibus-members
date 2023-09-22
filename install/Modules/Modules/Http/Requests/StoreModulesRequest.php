<?php

namespace Modules\Modules\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Modules\Rules\ModuleTableRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Modules\Rules\ModuleExistsRule;

class StoreModulesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'module_name' => [
                'required',
                'unique:modules,name,' . request('id'),
                new ModuleTableRule(),
                new ModuleExistsRule()
            ],
            'model_name' => 'required',
            'menu_title' => 'required|array',
            'menu_title.name' => 'required',
            'menu_title.icon' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.modules.store');
    }
}
