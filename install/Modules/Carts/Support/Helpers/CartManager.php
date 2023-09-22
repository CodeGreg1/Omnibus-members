<?php

if (!function_exists('cartManager')) {
    /**
     * @return \Modules\Carts\Services\CartManager
     */
    function cartManager()
    {
        return app('cart.manager');
    }
}