<?php

namespace Modules\Subscriptions\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Modules\Cashier\Facades\Cashier;
use Modules\Subscriptions\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'prices' => 'required|array|min:1',
            'features' => 'required|array|min:1',
            'prices.*.price' => 'required|numeric',
            'prices.*.compare_at_price' => 'required|numeric'
        ];

        if ($this->has('extra_conditions')) {
            $rules['extra_conditions'] = ['required', 'array'];
            $rules['extra_conditions.*.name'] = ['required'];
            $rules['extra_conditions.*.shortcode'] = [
                'required',
                'alpha_dash'
            ];
            $rules['extra_conditions.*.value'] = ['required'];
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Cashier::hasValidCilents()) {
                $validator->errors()->add('gateway', __('No available gateways. PLease set your payment gateways.'));
            }

            if ($this->isNameUsed()) {
                $validator->errors()->add('name', __('Name already in use'));
            }

            if (!$this->isPriceValid()) {
                $validator->errors()->add('price', __('Compare at price must be greater than the price.'));
            }

            if (!$this->uniqueConditionShortcodes()) {
                $validator->errors()->add('extra_conditions', __('Shortcodes must be unique accross a package.'));
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.subscriptions.packages.create');
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'features.required' => __('Please add atleast one feature for the package.'),
            'prices.required' => __('Please add atleast one price for the package.'),
            'prices.*.price' => __('Please provide price on your pricing detail.'),
            'prices.*.compare_at_price' => __('Please provide compare at price on your pricing detail.'),
            'extra_conditions.*.name.required' => __('Extra condition name is required.'),
            'extra_conditions.*.shortcode.required' => __('Extra condition shortcode is required.'),
            'extra_conditions.*.shortcode.unique' => __('Extra condition shortcode is taken.'),
            'extra_conditions.*.shortcode.alpha_dash' => __('Extra condition shortcode must only contain letters, numbers, dash and underscore.'),
            'extra_conditions.*.value.required' => __('Extra condition value is required.')
        ];
    }

    /**
     * Check if price is valid
     *
     * @return bool
     */
    protected function isPriceValid()
    {
        return !!collect($this->prices)->first(function ($price) {
            return $price['price'] < $price['compare_at_price'];
        });
    }

    /**
     * Check if package is unique
     *
     * @return bool
     */
    protected function isNameUsed()
    {
        return !!Package::where('name', $this->get('name'))->count();
    }

    /**
     * Check if shortcode are unique
     *
     * @return Boolean
     */
    protected function uniqueConditionShortcodes()
    {
        if ($this->has('extra_conditions') && count($this->get('extra_conditions'))) {
            $shortcodes = collect($this->get('extra_conditions'));
            $uniqueCount = $shortcodes->map(function ($item) {
                return $item['shortcode'];
            })->unique()->count();

            return $shortcodes->count() === $uniqueCount;
        }

        return true;
    }
}