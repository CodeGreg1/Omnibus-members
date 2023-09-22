<?php

if (!function_exists('enable_subscription_settings')) {
    /**
     * Enable all wallet related settings
     *
     * @return void
     */
    function enable_subscription_settings()
    {
        setting([
            'allow_subscriptions' => 'enable'
        ])->save();
    }
}