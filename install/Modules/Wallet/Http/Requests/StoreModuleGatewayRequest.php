<?php

namespace Modules\Wallet\Http\Requests;

use Modules\Base\Models\Currency;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreModuleGatewayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required'],
            'min_limit' => ['required', 'numeric', 'min:0'],
            'max_limit' => ['required', 'numeric', 'min:0'],
            'currency' => ['required'],
            'media_id' => 'exists:media,id',
        ];

        $rules['min_limit'][] = 'lt:' . $this->get('max_limit');
        $rules['max_limit'][] = 'gt:' . $this->get('min_limit');

        if ($this->get('charge_type') === 'fixed') {
            $rules['fixed_charge'] = ['required', 'numeric', 'min:0'];
            $currency = Currency::whereCode($this->get('currency'))->first();
            if (!$currency->decimal_digits) {
                $rules['fixed_charge'][] = 'integer';
            }
        }

        if ($this->get('charge_type') === 'percent') {
            $rules['percent_charge'] = 'required|numeric|min:0';
        }

        if ($this->get('charge_type') === 'both') {
            $rules['fixed_charge'] = ['required', 'numeric', 'min:0'];
            $rules['percent_charge'] = 'required|numeric|min:0';
            if (!isset($currency)) {
                $currency = Currency::whereCode($this->get('currency'))->first();
            }
            if (!$currency->decimal_digits) {
                $rules['fixed_charge'][] = 'integer';
            }
        }

        if ($this->has('user_data')) {
            $rules['user_data'] = ['required', 'array'];
            $rules['user_data.*.field_name'] = ['required'];
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'status' => 1
        ]);
        if ($this->has('user_data')) {
            $this->merge([
                'user_data' => json_decode($this->user_data, true),
            ]);
        }

        if ($this->get('charge_type') === 'percent') {
            $this->merge([
                'fixed_charge' => null
            ]);
        }

        if ($this->get('charge_type') === 'fixed') {
            $this->merge([
                'percent_charge' => null
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
        if ($this->has('user_data')) {
            $this->merge([
                'user_data' => json_encode($this->user_data, JSON_UNESCAPED_SLASHES),
            ]);

            $validator->after(
                function ($validator) {
                    if (!$this->uniqueUserDataField()) {
                        $validator->errors()->add('user_data', __('User data field must ne unique.'));
                    }
                }
            );
        }
    }

    public function uniqueUserDataField()
    {
        $userData = collect(json_decode($this->get('user_data'), true));
        $usersDataUnique = $userData->unique('field_name');
        return $userData->count() === $usersDataUnique->count();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'min_limit.lt' => __('Minimum limit must be less than the maximum limit.'),
            'max_limit.gt' => __('Maximum limit must be greater than the minimum limit.'),
            'user_data.*.field_name.required' => __('User Data: Field Name is required.'),
            'fixed_charge.integer' => __('Selected currency only accepts non-decimal value.')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.manual-gateways.store');
    }
}
