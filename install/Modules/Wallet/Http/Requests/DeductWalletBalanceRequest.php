<?php

namespace Modules\Wallet\Http\Requests;

use Modules\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class DeductWalletBalanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $amountRules = ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0.01'];

        $wallet = Wallet::where([
            'user_id' => $this->get('user'),
            'currency' => $this->get('currency')
        ])->first();

        if (!$wallet) {
            $amountRules[] = 'max:0';
        } else {
            $amountRules[] = 'max:' . number_format($wallet->balance, 2, '.', '');
        }

        return [
            'user' => 'required|exists:users,id',
            'currency' => 'required|exists:available_currencies,code',
            'amount' => $amountRules
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'amount.min' => __('Unprocessable amount. Please enter higher amount.'),
            'amount.max' => __('Not enough balance from this wallet.'),
            'amount.regex' => __('Amount must be a number with just two decimal points.'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.wallets.balances.deduct');
    }
}
