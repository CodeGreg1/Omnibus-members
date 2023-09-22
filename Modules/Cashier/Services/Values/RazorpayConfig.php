<?php

namespace Modules\Cashier\Services\Values;

class RazorpayConfig implements Config
{
    public function get()
    {
        return [
            'title' => 'Razorpay',
            'mode' => setting('cashier_mode', 'sandbox'),
            'api_id' => setting(setting('cashier_mode', 'sandbox') . '_razorpay_id'),
            'api_secret' => setting(setting('cashier_mode', 'sandbox') . '_razorpay_secret'),
            'webhook_secret' => setting(setting('cashier_mode', 'sandbox') . '_razorpay_webhook_secret'),
            'status' => setting('razorpay_status'),
            'logo' => setting('razorpay_logo'),
            'name' => setting('razorpay_display_name'),
            'service_base_namespace' => 'Modules\\Cashier\\Services\\Clients\\Razorpay\\Service',
            'view_instance' => [
                'cart' => [
                    'order', 'subscription', 'subscription_onetime'
                ]
            ],
            'currencies' => [
                'AED', 'ALL', 'AMD', 'ARS', 'AUD', 'AWG', 'BBD', 'BDT', 'BMD', 'BND', 'BOB', 'BSD', 'BWP', 'BZD', 'CAD', 'CHF', 'CNY', 'COP', 'CRC', 'CUP', 'CZK', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB', 'EUR', 'FJD', 'GBP', 'GHS', 'GIP', 'GMD', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'JMD', 'KES', 'KGS', 'KHR', 'KYD', 'KZT', 'LAK', 'LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MKD', 'MMK', 'MNT', 'MOP', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'PEN', 'PGK', 'PHP', 'PKR', 'QAR', 'RUB', 'SAR', 'SCR', 'SEK', 'SGD', 'SLL', 'SOS', 'SSP', 'SVC', 'SZL', 'THB', 'TTD', 'TZS', 'USD', 'UYU', 'UZS', 'YER', 'ZAR'
            ],
            'webhook_events' => [
                'Subscription Activated',
                'Subscription Charged',
                'Subscription Completed',
                'Subscription Updated',
                'Subscription Halted',
                'Subscription Cancelled',
                'Payment Captured',
                'Invoice Paid',
                'Payment Failed'
            ],
            'subscription_checkout_conditions' => [
                \Modules\Cashier\Services\Clients\Razorpay\Conditions\CheckoutViewAvailability::class
            ]
        ];
    }
}