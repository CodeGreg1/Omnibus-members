<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Models\Currency as ModelsCurrency;

class UpdateWalletSettings extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currencies = Currency::getCurrencies();
        $rules = [
            'wallet_exchange_charge_currency' => ['string', 'min:3'],
        ];
        $this->messages = [];
        foreach ($currencies as $code => $value) {
            $chargeAmountRule = $code . '_wallet_exchange_charge_amount';
            $rateChargeRule = $code . '_wallet_exchange_charge_rate';
            $sendChargeAmountRule = $code . '_wallet_send_money_charge_amount';
            $sendRateChargeRule = $code . '_wallet_send_money_charge_rate';

            $rules[$chargeAmountRule] = ['numeric'];
            $rules[$rateChargeRule] = ['numeric', 'max:100'];
            $rules[$sendChargeAmountRule] = ['numeric'];
            $rules[$sendRateChargeRule] = ['numeric', 'max:100'];

            $numericMessage = __('Fixed charge amount for :currency currency must be a number.', ['currency' => $code]);
            $percentNumericMessage = __('Percent amount for :currency currency must be a number.', ['currency' => $code]);
            $maxMessage = __('Percent amount for :currency currency must not be greater than 100', ['currency' => $code]);

            $this->messages[$chargeAmountRule . '.numeric'] = $numericMessage;
            $this->messages[$rateChargeRule . '.numeric'] = $percentNumericMessage;
            $this->messages[$rateChargeRule . '.max'] = $maxMessage;
            $this->messages[$sendChargeAmountRule . '.numeric'] = $numericMessage;
            $this->messages[$sendRateChargeRule . '.numeric'] = $percentNumericMessage;
            $this->messages[$sendRateChargeRule . '.max'] = $maxMessage;


            if (!$value['model']['decimal_digits']) {
                $integerMessage = __(':currency currency only supports non-decimal amount.', ['currency' => $code]);
                $rules[$chargeAmountRule][] = 'integer';
                $this->messages[$chargeAmountRule . '.integer'] = $integerMessage;

                $rules[$sendChargeAmountRule][] = 'integer';
                $this->messages[$sendChargeAmountRule . '.integer'] = $integerMessage;
            }
        }

        return  $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.settings.wallet.update');
    }
}
