<?php

namespace Modules\Subscriptions\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Subscriptions\Models\PackageExtraCondition;

class StorePackageExtraConditionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'shortcode' => 'required',
            'value' => 'required'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(
            function ($validator) {
                if ($this->conditionShortcodeExists()) {
                    $validator->errors()->add('shortcode', __('Shortcode already taken.'));
                }
            }
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.subscriptions.packages.extra-conditions.store');
    }

    protected function conditionShortcodeExists()
    {
        return PackageExtraCondition::where([
            'shortcode' => $this->get('shortcode'),
            'package_id' => $this->id
        ])->exists();
    }
}
