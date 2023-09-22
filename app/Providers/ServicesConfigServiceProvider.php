<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServicesConfigServiceProvider extends ServiceProvider
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
        $this->setAuthyKey();
        $this->setGoogleLoginApi();
        $this->setFacebookLoginApi();
    }

    protected function setAuthyKey() 
    {
        config([
            'services.authy.key' => 
                setting(
                    'services_authy_key', 
                    env('AUTHY_KEY')
                )
        ]);
    }

    protected function setGoogleLoginApi() 
    {
        config([
            'services.google.client_id' => 
                setting(
                    'services_google_client_id', 
                    env('GOOGLE_CLIENT_ID')
                )
        ]);

        config([
            'services.google.client_secret' => 
                setting(
                    'services_google_client_secret', 
                    env('GOOGLE_CLIENT_SECRET')
                )
        ]);

        config([
            'services.google.redirect' => 
                setting(
                    'services_google_redirect', 
                    env('GOOGLE_CALLBACK_URI')
                )
        ]);
    }

    protected function setFacebookLoginApi() 
    {
        config([
            'services.facebook.client_id' => 
                setting(
                    'services_facebook_app_id', 
                    env('FACEBOOK_CLIENT_ID')
                )
        ]);

        config([
            'services.facebook.client_secret' => 
                setting(
                    'services_facebook_app_secret', 
                    env('FACEBOOK_CLIENT_SECRET')
                )
        ]);

        config([
            'services.facebook.redirect' => 
                setting(
                    'services_facebook_redirect', 
                    env('FACEBOOK_CALLBACK_URI')
                )
        ]);
    }
}
