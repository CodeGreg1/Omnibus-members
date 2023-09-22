<?php

namespace Modules\Subscriptions\Services\Concerns;

use Modules\Payments\Services\Payment;
use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Facades\Subscription as FacadesSubscription;

trait ManagesSubscriptions
{
    /**
     * Get all of the subscriptions for the Stripe model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->subscribables()->orderBy('created_at', 'desc');
    }

    public function subscriptionExist($id, $gateway = null)
    {
        return $this->subscriptions()->where('ref_profile_id', $id)
            ->when($gateway, function ($query, $gateway) {
                $query->where('gateway', $gateway);
            })
            ->exists();
    }

    /**
     * Get a subscription instance by name.
     *
     * @param  string  $name
     * @return \Modules\Subscriptions\Models\Subscription|null
     */
    public function subscription()
    {

        return $this->latestSubscription;
    }

    public function hasAnySubscription()
    {
        return !!$this->subscriptions()->count();
    }

    /**
     * Get the user's most recent subscription.
     *
     * @return \Modules\Subscriptions\Models\Subscription|null
     */
    public function latestSubscription()
    {
        return $this->morphOne(Subscription::class, 'subscribable')->latestOfMany();
    }

    /**
     * Determine if the Stripe model has a given subscription.
     *
     * @param  string  $name
     * @param  string|null  $price
     * @return bool
     */
    public function subscribed($price = null, $active = false)
    {
        $subscription = $this->subscription();

        if (!$subscription) {
            return false;
        }

        if (!$subscription->valid()) {
            return false;
        } else {
            if ($active && $subscription->canceled()) {
                return false;
            }
        }

        return !$price || $subscription->package_price_id === intval($price);
    }

    /**
     * Determine if the Stripe model is actively subscribed to one of the given prices.
     *
     * @param  string|string[]  $prices
     * @param  string  $name
     * @return bool
     */
    public function subscribedToPrice($prices, $active = false)
    {
        $subscription = $this->subscription();

        if (!$subscription) {
            return false;
        }

        if ($subscription->ended() || !$subscription->valid()) {
            return false;
        }

        foreach ((array) $prices as $price) {
            if ($subscription->hasPrice($price)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Scope a query to only include active users subscriptions.
     * This includes cancelled but is still in grace period
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsSubscriptionActive($query)
    {
        return $query->whereHas('latestSubscription', function ($q) {
            $q->where(function ($q) {
                $q->where('ended_at', '>=', now())
                    ->orWhereNull('ended_at');
            })
                ->where(function ($q) {
                    $q->where('ends_at', '>=', now())
                        ->orWhereNull('ends_at');
                })
                ->whereNull('canceled_at')
                ->whereNull('trial_ends_at');
        });
    }

    /**
     * Scope a query to only include active users.
     * This includes cancelled but is still in grace period
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsSubscriptionCancelled($query)
    {
        return $query->whereHas('latestSubscription', function ($q) {
            $q->whereNotNull('canceled_at')->where('canceled_at', '<=', now());
        });
    }

    /**
     * Scope a query to only include trialing users subscriptions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsSubscriptionTrailing($query)
    {
        return $query->whereHas('latestSubscription', function ($q) {
            $q->onTrial();
        });
    }


    /**
     * Scope a query to only include ended users subscriptions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsSubscriptionEnded($query)
    {
        return $query->whereHas('latestSubscription', function ($q) {
            $extension = intval(setting('subscription_expiration_extension'));
            $q->whereNull('canceled_at')
                ->where('ends_at', '<', now()->subDays($extension))
                ->where(function ($q) {
                    $q->where('ended_at', '<', now())
                        ->orWhereNull('ended_at');
                });
        });
    }

    /**
     * Scope a query to only include users with no subscriptions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoSubscription($query)
    {
        return $query->doesntHave('subscriptions');
    }

    /**
     * Scope a query to only include users with past due subscription.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsSubscriptionPastDue($query)
    {
        return $query->whereHas('latestSubscription', function ($q) {
            $extension = intval(setting('subscription_expiration_extension'));
            $q->where(function ($q) {
                $q->where('ended_at', '>=', now())
                    ->orWhereNull('ended_at');
            })
                ->whereBetween('ends_at', [now()->subDays($extension), now()])
                ->whereNull('canceled_at')
                ->whereNull('trial_ends_at');
        });
    }

    /**
     * Check if user has active subscription.
     */
    public function isSubscriptionActive()
    {
        if (!$this->hasAnySubscription()) {
            return false;
        }

        $subscription = $this->latestSubscription;
        if ($subscription->ended()) {
            return false;
        }

        if ($subscription->canceled()) {
            return false;
        }

        if ($subscription->ends_at) {
            if ($subscription->ends_at->isFuture()) {
                return true;
            }

            if ($subscription->onGracePeriod()) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Check if latest subscription of user is canceled.
     */
    public function isSubscriptionCancelled()
    {
        if (!$this->hasAnySubscription()) {
            return false;
        }

        return $this->latestSubscription->canceled();
    }

    /**
     * Check if latest subscription of user is on trial.
     */
    public function isSubscriptionTrailing()
    {
        if (!$this->hasAnySubscription()) {
            return false;
        }

        return $this->latestSubscription->onTrial();
    }

    /**
     * Check if latest subscription of user is on past due.
     */
    public function isSubscriptionPastDue()
    {
        if (!$this->hasAnySubscription()) {
            return false;
        }

        $subscription = $this->latestSubscription;
        if ($subscription->ended()) {
            return false;
        }

        if ($subscription->canceled()) {
            return false;
        }

        if ($subscription->ends_at) {
            return $subscription->onGracePeriod();
        }

        return false;
    }

    /**
     * Check if latest subscription of user is ended.
     */
    public function isSubscriptionEnded()
    {
        if (!$this->hasAnySubscription()) {
            return false;
        }

        if (
            $this->latestSubscription->ended_at
            && !$this->latestSubscription->ended_at->isFuture()
        ) {
            return true;
        }

        if ($this->latestSubscription->ends_at) {
            if ($this->latestSubscription->onGracePeriod()) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Check if has noe subscription.
     */
    public function noSubscription()
    {
        return !$this->hasAnySubscription();
    }

    /**
     * Change subscription package price
     */
    public function changeSubscriptionPrice(PackagePrice $price)
    {
        $subscription = $this->latestSubscription;
        $response = FacadesSubscription::provider($subscription->gateway)->changePrice($subscription, $price);
        if ($response) {
            $paymentPayload = null;
            if (isset($response['payment'])) {
                $paymentPayload = $response['payment'];
                unset($response['payment']);
            }

            $subscription->fill($response);
            $subscription->save();
            if ($subscription->hasDiscount()) {
                $subscription->discount()->delete();
            }

            if ($paymentPayload) {
                Payment::make($subscription, array_merge(
                    $paymentPayload,
                    [
                        'gateway' => $subscription->gateway,
                    ]
                ));
            }
        }
    }
}