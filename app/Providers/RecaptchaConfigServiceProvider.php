<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RecaptchaConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        config([
            'recaptcha.api_site_key' => 
                setting(
                    'recaptcha_site_key', 
                    env('RECAPTCHA_SITE_KEY')
                )
        ]);

        config([
            'recaptcha.api_secret_key' => 
                setting(
                    'recaptcha_secret_key', 
                    env('RECAPTCHA_SECRET_KEY')
                )
        ]);
    }
}
