<?php

namespace Modules\Subscriptions\Policies;

use App\Models\User;
use Modules\Subscriptions\Models\Subscription;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given subscription can be viewed by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \Modules\Subscriptions\Models\Subscription  $subscription
     * @return bool
     */
    public function view(User $user, Subscription $subscription)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $subscription->subscribable->id === $user->id;
    }

    /**
     * Determine if the given subscription can be cancelled by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \Modules\Subscriptions\Models\Subscription  $subscription
     * @return bool
     */
    public function cancel(User $user, Subscription $subscription)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $subscription->subscribable->id === $user->id;
    }
}
