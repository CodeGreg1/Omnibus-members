<?php

namespace Modules\Pages\Providers;

use Illuminate\Support\ServiceProvider;

class PageBuilderServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerFacades();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            //
        ];
    }

    protected function registerFacades()
    {
        $this->app->singleton('PageBuilder', function () {
            return new \Modules\Pages\Services\PageBuilder();
        });
    }
}