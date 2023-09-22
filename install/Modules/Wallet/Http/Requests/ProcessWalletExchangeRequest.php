<?php

namespace Modules\Wallet\Http\Requests;

use Modules\Base\Models\Currency;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProcessWalletExchangeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'amount' => ['required', 'numeric', 'gt:0'],
            'from_currency' => ['required'],
            'to_currency' => ['required', 'different:from_currency']
        ];
        $max = 0;
        $this->wallet = $this->user()->getWalletByCurrency($this->get('from_currency'));
        if ($this->wallet) {
            $max = $this->wallet->balance;
        }

        $rules['amount'][] = 'max:' . $max;

        $currency = Currency::whereCode($this->get('from_currency'))->first();
        if (!$currency->decimal_digits) {
            $rules['amount'][] = 'integer';
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
        $validator->after(
            function ($validator) {
                if (!$this->validAmount()) {
                    $validator->errors()->add('amount', __('Amount too low.'));
                }

                if (setting('allow_wallet_multi_currency') !== 'enable') {
                    $validator->errors()->add('message', __('Wallet conversion not supported.'));
                }
            }
        );
    }

    protected function validAmount()
    {
        $amount = floatval($this->get('amount'));;
        $fixedCharge = floatval(currency_format(wallet_charge($this->get('from_currency')), $this->get('from_currency'), false));
        $rate = floatval(setting('wallet_exchange_percent_charge_rate', 0));
        $charge = $fixedCharge;

        $percentCharge = 0;
        if ($rate > 0) {
            $percentCharge = ($rate / 100) * $amount;
            $charge = $charge + $percentCharge;
        }

        return $amount > $charge;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'amount.gt' => __('Amount must not be 0.'),
            'amount.max' => __('Insufficient balance from this wallet.'),
            'amount.integer' => __('Selected currency cannot contain decimal amount.'),
            'to_currency.different' => __('Please select different wallet.')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.profile.wallet.exchange.process');
    }
}
