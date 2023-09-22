<?php

namespace Modules\Withdrawals\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Wallet\Models\ManualGateway;
use Illuminate\Foundation\Http\FormRequest;

class StoreWithdrawCheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->customMessage = [];
        $method = ManualGateway::findOrFail($this->get('method'));

        $this->withdrawMethod = $method;

        $amountRules = ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'];
        if ($method->min_limit) {
            $amountRules[] = 'min:' . number_format($method->min_limit, 2, '.', '');
        }

        $wallet = auth()->user()->getWalletByCurrency($method->currency);

        if (!$wallet) {
            $amountRules[] = 'max:0';
            $this->customMessage['amount.max'] = __('Not enough balance from this wallet.');
        } else {
            $maxValue = $method->max_limit ?? 0;

            if ($wallet->balance < $maxValue) {
                $rule = 'max:' . number_format($wallet->balance, 2, '.', '');
                $amountRules[] = $rule;
                $this->customMessage['amount.max'] = __('Not enough balance from this wallet.');
            } else {
                $rule = 'max:' . number_format($maxValue, 2, '.', '');
                $amountRules[] = $rule;
                $this->customMessage['amount.max'] = __('Amount exceeds method limit.');
            }
        }

        $rules = [
            'amount' => $amountRules
        ];

        if ($method->user_data && count($method->user_data)) {
            foreach ($method->user_data as $key => $value) {
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
            'amount.regex' => __('Amount must only have 2 decimal places.')
        ];
        if ($this->withdrawMethod->user_data && count($this->withdrawMethod->user_data)) {
            foreach ($this->withdrawMethod->user_data as $key => $value) {
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

        return array_merge($messages, $this->customMessage);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.withdrawals.checkout.process');
    }
}
