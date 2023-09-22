<?php

if (!function_exists('storageManager')) {
    /**
     * @return \Modules\Carts\Storage\StorageManager
     */
    function storageManager()
    {
        return app('cart.storage');
    }
}