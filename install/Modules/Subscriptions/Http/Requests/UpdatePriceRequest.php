<?php

namespace Modules\Subscriptions\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'price' => 'required',
            'currency' => 'required|exists:available_currencies,code',
            'type' => 'required'
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
            'price' => $this->request->get('price') ?? 0,
            'compare_at_price' => $this->request->get('compare_at_price') ?? 0,
            'trial_days_count' => $this->request->get('trial_days_count') ?? 0,
        ]);

        if ($this->request->get('type') === 'onetime') {
            $this->merge([
                'trial_days_count' => 0,
                'package_term_id' => null
            ]);
        }
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
            if (!$this->isPriceValid()) {
                $validator->errors()->add('gateway', __('Compare at price must be greater than the price.'));
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
        return Gate::allows('admin.subscriptions.packages.prices.update');
    }

    /**
     * Check if price is valid
     *
     * @return bool
     */
    protected function isPriceValid()
    {
        if ($this->request->get('price')) {
            return $this->request->get('price') < $this->request->get('compare_at_price');
        }

        return true;
    }
}