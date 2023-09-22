<?php

use Modules\Payments\Services\DompdfInvoiceRenderer;

return [
    'name' => __('Cashier'),

    'path' => env('CASHIER_PATH', 'cashier'),

    /*
    |--------------------------------------------------------------------------
    | Default Storage Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default cache driver that should be used
    | by the framework.
    |
    | Supported: all cache drivers supported by Laravel
    |
    */

    'cache_driver' => null,

    'clients' => [
        'paypal' => Modules\Cashier\Services\Values\PaypalConfig::class,
        'stripe' => Modules\Cashier\Services\Values\StripeConfig::class,
        'razorpay' => Modules\Cashier\Services\Values\RazorpayConfig::class,
        'mollie' => Modules\Cashier\Services\Values\MollieConfig::class,
        'manual' => [
            'service_base_namespace' => 'Modules\\Cashier\\Services\\Clients\\Manual\\Service',
            'status' => 'active',
            'button' => [
                'class' => 'btn btn-primary btn-lg mb-4 btn-pay-pricing',
                'href' => '',
                'content' => 'Subscribe'
            ]
        ]
    ],

    'currency' => 'USD',

    'invoices' => [
        'renderer' => env('CASHIER_INVOICE_RENDERER', DompdfInvoiceRenderer::class),

        'options' => [
            // Supported: 'letter', 'legal', 'A4'
            'paper' => env('CASHIER_PAPER', 'letter'),
        ],
    ],
];