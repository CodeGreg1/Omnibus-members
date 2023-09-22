<?php

namespace Modules\Cashier\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepositSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->get($this->get('gateway') . '_deposit_min_limit')) {
            $rules[$this->get('gateway') . '_deposit_min_limit'] = ['numeric'];
            if ($this->get($this->get('gateway') . '_deposit_max_limit')) {
                $rules[$this->get('gateway') . '_deposit_min_limit'][] = 'lt:' . $this->get($this->get('gateway') . '_deposit_max_limit');
            }
        }

        if ($this->get($this->get('gateway') . '_deposit_max_limit')) {
            $rules[$this->get('gateway') . '_deposit_max_limit'] = ['numeric'];
            if ($this->get($this->get('gateway') . '_deposit_min_limit')) {
                $rules[$this->get('gateway') . '_deposit_max_limit'][] = 'gt:' . $this->get($this->get('gateway') . '_deposit_min_limit');
            }
        }

        if ($this->get($this->get('gateway') . '_deposit_fixed_charge')) {
            $rules[$this->get('gateway') . '_deposit_fixed_charge'] = ['numeric'];
        }

        if ($this->get($this->get('gateway') . '_deposit_percent_charge')) {
            $rules[$this->get('gateway') . '_deposit_percent_charge'] = ['numeric', 'lt:100'];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $gateway = $this->get('gateway');

        return [
            $gateway . '_deposit_min_limit.numeric' => __('Minimum limit must be a number.'),
            $gateway . '_deposit_min_limit.lt' => __('Minimum limit must be less than the maximum limit.'),
            $gateway . '_deposit_max_limit.numeric' => __('Maximum limit must be a number.'),
            $gateway . '_deposit_max_limit.lt' => __('Maximum limit must be less than the minimum limit.'),
            $gateway . '_deposit_fixed_charge.numeric' => __('Fixed charge must be a number.'),
            $gateway . '_deposit_percent_charge.numeric' => __('Percent charge must be a number.'),
            $gateway . '_deposit_percent_charge.lt' => __('Percent charge must be less than 100.')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.settings.payment-gateways.deposit-settings.update');
    }
}
