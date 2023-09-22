<?php

return [
    'name' => 'Carts',

    'format_numbers' => true,

    'decimals' => 2,

    'dec_point' => '.',

    'thousands_sep' => ',',

    'instances' => [
        'cart' => [
            'storage' => 'session',
        ],

        /*
        'cart' => [
            'storage' => 'eloquent',
        ],
        */

        'wishlist' => [
            'storage' => 'session',
            // Any other option will be passes through to the store driver
            'prefix'  => 'wishlist_',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default cart instance
    |--------------------------------------------------------------------------
    |
    | When you add something to the cart, which instance gets selected by default
    */
    'default'   => 'cart',

    /*
     * ---------------------------------------------------------------
     * persistence
     * ---------------------------------------------------------------
     *
     * the configuration for persisting cart
     */
    'storage' => [
        'item_key'    => 'cart_item_id',

        'stores' => [

            'session' => [],

            'cache' => [
                'driver'      => 'default',
            ],

            'cookie' => [
                'fallback'    => 'session',
                'expires'     => 1440
            ],

            'db' => [
                'fallback'    => 'session',
                'modelClasses'      => [
                    'cart_items' => Modules\Carts\Models\CartItem::class,
                    'cart_conditions' => Modules\Carts\Models\CartCondition::class,
                ],
                'cart_key'    => 'session_id'
            ],
        ],

        'default' => 'session',
    ],

    /*
     * ---------------------------------------------------------------
     * events
     * ---------------------------------------------------------------
     *
     * the configuration for cart events
     */
    'events' => null,

    'classes' => [
        'cart' => Modules\Carts\Services\Cart::class,
        'session' => App\Models\User::class,
        'cartItem' => Modules\Carts\Models\CartItem::class,
        'storageManager' => Modules\Carts\Storage\StorageManager::class,
        'cartManager' => Modules\Carts\Services\CartManager::class
    ]
];