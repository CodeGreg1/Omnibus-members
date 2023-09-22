<?php

namespace Modules\Cashier\Services\Values;

class MollieConfig implements Config
{
    public function get()
    {
        return [
            'title' => 'Mollie',
            'mode' => setting('cashier_mode', 'sandbox'),
            'api_key' => setting(setting('cashier_mode', 'sandbox') . '_mollie_api_key'),
            'webhook_id' => setting(setting('cashier_mode', 'sandbox') . '_mollie_webhook_id'),
            'status' => setting('mollie_status'),
            'logo' => setting('mollie_logo'),
            'name' => setting('mollie_display_name'),
            'service_base_namespace' => 'Modules\\Cashier\\Services\\Clients\\Mollie\\Service',
            'view_instance' => [
                'cart' => [
                    'order', 'subscription', 'subscription_onetime'
                ]
            ],
            'currencies' => ['EUR', 'AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR']
        ];
    }
}