<?php

if (!function_exists('wallet_charge')) {
    /**
     * Get wallet charge amount.
     *
     * @param string $code
     *
     * @return float
     */
    function wallet_charge($code)
    {
        return floatval(setting($code . '_wallet_exchange_charge_amount', 0));
    }
}

if (!function_exists('wallet_rate')) {
    /**
     * Get wallet charge rate.
     *
     * @param string $code
     *
     * @return float
     */
    function wallet_rate($code)
    {
        return floatval(setting($code . '_wallet_exchange_charge_rate', 0));
    }
}

if (!function_exists('wallet_send_charge')) {
    /**
     * Get wallet send money charge amount.
     *
     * @param string $code
     *
     * @return float
     */
    function wallet_send_charge($code)
    {
        return floatval(setting($code . '_wallet_send_money_charge_amount') ?? 0);
    }
}

if (!function_exists('wallet_send_rate')) {
    /**
     * Get wallet send money charge rate.
     *
     * @param string $code
     *
     * @return float
     */
    function wallet_send_rate($code)
    {
        return floatval(setting($code . '_wallet_send_money_charge_rate') ?? 0);
    }
}

if (!function_exists('enable_wallet_settings')) {
    /**
     * Enable all wallet related settings
     *
     * @return void
     */
    function enable_wallet_settings()
    {
        setting([
            'allow_wallet' => 'enable',
            'allow_withdrawal' => 'enable',
            'allow_send_money' => 'enable',
            'allow_wallet_multi_currency' => 'enable'
        ])->save();
    }
}