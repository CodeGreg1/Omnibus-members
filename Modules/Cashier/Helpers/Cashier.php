<?php

if (!function_exists('cashier')) {
    /**
     * @return \Modules\Cashier\Facades\Cashier
     */
    function cashier()
    {
        return app('cashier');
    }
}