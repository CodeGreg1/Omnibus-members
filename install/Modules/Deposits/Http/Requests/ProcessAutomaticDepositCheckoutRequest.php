<?php

namespace Modules\Deposits\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Foundation\Http\FormRequest;

class ProcessAutomaticDepositCheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $names = explode('.', $this->route()->getName());
        $gateway = $names[count($names) - 2];
        $min = setting($gateway . '_deposit_min_limit') ?? 0;
        $max = setting($gateway . '_deposit_max_limit') ?? 0;
        $currency = setting('currency', config('cashier.currency'));
        $amountRules = ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'];
        $gatewayClient = Cashier::getClient($gateway);
        $zero_decimal_currencies = $gatewayClient->getConfig('zero_decimal_currencies');
        if ($zero_decimal_currencies && count($zero_decimal_currencies)) {
            if (in_array($this->get('currency'), $zero_decimal_currencies)) {
                $amountRules[] = 'integer';
            }
        }

        if ($min) {
            $minValue = currency($min, $currency, $this->get('currency'), false);
            $amountRules[] = 'min:' . number_format($minValue, 2, '.', '');
        }

        if ($max) {
            $maxValue = currency($max, $currency, $this->get('currency'), false);
            $amountRules[] = 'max:' . number_format($maxValue, 2, '.', '');
        }

        return [
            'currency' => 'required',
            'amount' => $amountRules
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (setting('allow_wallet_multi_currency') !== 'enable') {
            $this->merge([
                'currency' => setting('currency'),
            ]);
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'amount.min' => __('The deposit amount must be at least the minimum amount.'),
            'amount.max' => __('Amount exceeds the maximum deposit amount.'),
            'amount.regex' => __('Amounts must only have 2 decimal places.'),
            'amount.integer' => __('The currency only accepts non-decimal amounts.')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // $names = explode('.', $this->route()->getName());
        // $gateway = $names[count($names) - 2];
        // return Gate::allows('user.deposits.checkout.' . $gateway . '.process');
    }
}
