<?php

if (!function_exists('enable_affiliate')) {
    /**
     * Enable affiliate feature
     *
     * @return void
     */
    function enable_affiliate()
    {
        setting([
            'allow_affiliates' => 'enable'
        ])->save();
    }
}