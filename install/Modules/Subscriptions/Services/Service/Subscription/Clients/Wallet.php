<?php

namespace Modules\Subscriptions\Services\Service\Subscription\Clients;

use Modules\Subscriptions\Models\PackagePrice;
use Modules\Subscriptions\Models\Subscription;
use Modules\Wallet\Exceptions\InsufficientBalance;
use Modules\Subscriptions\Services\Service\Subscription\SubscriptionServiceClient;

class Wallet extends SubscriptionServiceClient
{
    public function getCustomerConsentUrl(Subscription $subscription, PackagePrice $price)
    {
        return route('user.subscriptions.change-package.complete', [
            'subscription' => $subscription->id
        ]);
    }

    public function completeChangePrice(
        Subscription $subscription,
        PackagePrice $price,
        $attributes = []
    ) {
        $wallet = $subscription->subscribable->getWalletByCurrency($price->currency);
        if (!$wallet) {
            throw InsufficientBalance::insufficient();
        }

        if ($price->price > $wallet->balance) {
            throw InsufficientBalance::insufficient();
        }
        $subscription->cancel('now');

        $subscription->subscribable->unLoadWallet($wallet, $price->price);

        $ends_at = now()->add(
            $price->term->interval,
            $price->term->interval_count
        )->format('Y-m-d H:i:s');

        return [
            'ref_profile_id' => uniqid('wlt-'),
            'name' => 'main',
            'trial_ends_at' => null,
            'starts_at' => now()->format('Y-m-d H:i:s'),
            'ends_at' => $ends_at,
            'gateway' => 'wallet',
            'payment' => [
                'transaction_id' => uniqid('trx-'),
                'currency' => $wallet->currency,
                'total' => $price->price
            ]
        ];
    }
}

// 1,985.00
