<?php

namespace Modules\Deposits\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Wallet\Models\ManualGateway;
use Illuminate\Foundation\Http\FormRequest;

class ProcessManualDepositCheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $gateway = ManualGateway::findOrFail($this->route('method'));
        $this->gateway = $gateway;

        $amountRules = ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'];
        if ($gateway->min_limit) {
            $amountRules[] = 'min:' . number_format($gateway->min_limit, 2, '.', '');
        }

        if ($gateway->max_limit) {
            $amountRules[] = 'max:' . number_format($gateway->max_limit, 2, '.', '');
        }

        $rules = [
            'amount' => $amountRules
        ];

        if ($gateway->user_data && count($gateway->user_data)) {
            foreach ($gateway->user_data as $key => $value) {
                if ($value->required) {
                    $rules['user_data.' . $value->field_name] = ['required'];
                    if ($value->field_type === 'image_upload') {
                        $rules['user_data.' . $value->field_name][] = ['image'];
                    }
                }
            }
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
        $messages = [
            'amount.min' => __('The deposit must be at least the minimum amount.'),
            'amount.max' => __('Amount exceeds the maximum deposit amount.'),
            'amount.regex' => __('Amounts must only have 2 decimal places.')
        ];

        if ($this->gateway->user_data && count($this->gateway->user_data)) {
            foreach ($this->gateway->user_data as $key => $value) {
                if ($value->required) {
                    $messages['user_data.' . $value->field_name . '.required'] = __('The user data: :field is required.', [
                        'field' => ucfirst(strtolower($value->field_name))
                    ]);
                    if ($value->field_type === 'image_upload') {
                        $messages['user_data.' . $value->field_name . '.image'] = __('The user data: :field must be a valid image.', [
                            'field' => ucfirst(strtolower($value->field_name))
                        ]);
                    }
                }
            }
        }

        return $messages;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.deposits.checkout.manual.process');
    }
}
