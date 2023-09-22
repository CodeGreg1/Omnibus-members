<?php

namespace Modules\Reports\Support;

use App\Models\User;
use Illuminate\Support\Carbon;
use Modules\Subscriptions\Models\Subscription;
use Modules\AvailableCurrencies\Facades\Currency;

class BillingReport
{
    public function get($startDate, $endDate)
    {
        $range = $this->geDates($startDate, $endDate);
        $subscriptions = Subscription::with(['payables'])
            ->has('subscribable')
            ->whereBetween('created_at', $range)
            ->get();

        return [
            'signup_revenue' => $this->getSignupRevenue($subscriptions),
            'renewal_revenue' => $this->getRenewalRevenue($subscriptions),
            'subscription_renewal' => $this->getSubscriptionRenewal($subscriptions),
            'total_subscriptions' => $subscriptions->count(),
            'subscription_cancellations' => $this->getSubscriptionCancellations($subscriptions),
            'subscriptions_ended' => $this->getSubscriptionEnded($subscriptions),
            'current_subscriptions' => $this->getCurrentSubscriptions($subscriptions),
            'trialing_subscriptions' => $this->getTrialingSubscriptions($subscriptions, $range)
        ];
    }

    protected function getSignupRevenue($subscriptions)
    {
        $currency = Currency::getUserCurrency();
        return currency_format($subscriptions->sum(function ($subscription) use ($currency) {
            return $subscription->payables
                ->filter(function ($payment, $key) {
                    return $payment->state->getValue() === 'paid';
                })
                ->reduce(function ($carry, $payment) use ($currency) {
                    return $carry + currency(
                        floatval($payment->total),
                        $payment->currency,
                        $currency,
                        false
                    );
                }, 0);
        }), $currency);
    }

    protected function getRenewalRevenue($subscriptions)
    {
        $currency = Currency::getUserCurrency();
        return currency_format($subscriptions
            ->filter(function ($subscription, $key) {
                return $subscription->payables()->count() > 1;
            })
            ->sum(function ($subscription) use ($currency) {
                return $subscription->payables
                    ->skip(1)
                    ->filter(function ($payment, $key) {
                        return $payment->state->getValue() === 'paid';
                    })
                    ->reduce(function ($carry, $payment) use ($currency) {
                        return $carry + currency(
                            floatval($payment->total),
                            $payment->currency,
                            $currency,
                            false
                        );
                    }, 0);
            }), $currency);
    }

    protected function getSubscriptionRenewal($subscriptions)
    {
        return $subscriptions
            ->filter(function ($subscription, $key) {
                return $subscription->payables()->count() > 1;
            })->count();
    }

    protected function getSubscriptionSignup($subscriptions)
    {
        return $subscriptions
            ->filter(function ($subscription, $key) {
                return $subscription->created_at;
            })->count();
    }

    protected function getSubscriptionCancellations($subscriptions)
    {
        return $subscriptions
            ->filter(function ($subscription, $key) {
                return $subscription->getStatus() === 'cancelled';
            })->count();
    }

    protected function getSubscriptionEnded($subscriptions)
    {
        return $subscriptions
            ->filter(function ($subscription, $key) {
                return $subscription->getStatus() === 'ended';
            })->count();
    }

    protected function getCurrentSubscriptions($subscriptions)
    {
        return $subscriptions
            ->filter(function ($subscription, $key) {
                return $subscription->getStatus() === 'active';
            })->count();
    }

    protected function getTrialingSubscriptions($subscriptions)
    {
        return $subscriptions
            ->filter(function ($subscription, $key) {
                return $subscription->getStatus() === 'trialing';
            })->count();
    }

    protected function geDates($startDate, $endDate)
    {
        return [
            Carbon::create($startDate),
            Carbon::create($endDate)
        ];
    }
}
