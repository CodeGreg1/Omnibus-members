<?php

namespace Modules\Wallet\Http\Requests;

use Modules\Base\Models\Currency;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProcessWalletSendMoneyRequest extends FormRequest
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
            'currency' => ['required'],
            'email' => ['email', 'exists:users,email', 'not_in:' . $this->user()->email]
        ];

        $max = 0;
        $this->wallet = $this->user()->getWalletByCurrency($this->get('currency'));
        if ($this->wallet) {
            $amount = floatval($this->get('amount'));
            $currency = $this->get('currency');
            $fixedCharge = floatval(currency_format(
                wallet_send_charge($currency),
                $currency,
                false
            ));
            $rate = wallet_send_rate($currency);

            $charge = $fixedCharge;
            $rateCharge = 0;
            if ($rate > 0) {
                $rateCharge = floatval(currency_format((($rate / 100) * $amount), $currency, false));
                $charge = $charge + $rateCharge;
            }

            $total = $charge + $amount;

            $max = $this->wallet->balance - $charge;

            $this->rate = $rate;
            $this->amount = $amount;
            $this->rateCharge = $rateCharge;
            $this->fixedCharge = $fixedCharge;
            $this->charge = $charge;
            $this->total = $total;
        }

        $rules['amount'][] = 'max:' . $max;

        $currency = Currency::whereCode($this->get('currency'))->first();
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
                if (setting('allow_send_money') !== 'enable') {
                    $validator->errors()->add('message', __('Wallet send not supported.'));
                }
            }
        );
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.exists' => __('User not found.'),
            'amount.gt' => __('Amount must not be 0.'),
            'amount.max' => __('Insufficient balance from this wallet.'),
            'amount.integer' => __('Selected currency cannot contain decimal amount.'),
            'email.not_in' => __('It is not possible to send to one\'s own account.'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.profile.wallet.send-money.process');
    }
}
