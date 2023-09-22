<?php

namespace Modules\Subscriptions\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Subscriptions\View\Composers\SubscriptionComposer;
use Modules\Subscriptions\View\Composers\SubscriptionSalesOverview;
use Modules\Subscriptions\View\Composers\SubscriptionWidgetComposer;

class SubscriptionsViewProvider extends ServiceProvider
{
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
        return [];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        View::composer(
            [
                'users::admin.show',
                'users::admin.edit',
                'users::admin.edit-user-settings',
                'subscriptions::admin.user.billing'
            ],
            SubscriptionComposer::class
        );

        View::composer(['dashboard::index'], SubscriptionWidgetComposer::class);
        View::composer(['dashboard::index'], SubscriptionSalesOverview::class);
    }
}