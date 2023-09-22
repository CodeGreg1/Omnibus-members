<?php

namespace Modules\Subscriptions\Providers;

use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Policies\SubscriptionPolicy;
use App\Providers\AuthServiceProvider as ProvidersAuthServiceProvider;

class AuthServiceProvider extends ProvidersAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Subscription::class => SubscriptionPolicy::class,
    ];
}
