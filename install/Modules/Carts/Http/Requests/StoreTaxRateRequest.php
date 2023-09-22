<?php

namespace Modules\Carts\Http\Requests;

use Modules\Carts\Models\TaxRate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaxRateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'percentage' => 'required|numeric',
            'tax_type' => 'required'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'active' => $this->has('active') ? 1 : 0
        ]);
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
            if ($this->isTitleUsed()) {
                $validator->errors()->add('title', __('Title already in use'));
            }
        });
    }

    /**
     * Check if tax rate is unique
     *
     * @return bool
     */
    protected function isTitleUsed()
    {
        return !!TaxRate::where('title', $this->get('title'))->count();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.tax-rates.store');
    }
}