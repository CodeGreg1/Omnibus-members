<?php

namespace Modules\Carts\Services\Clients\Subscription;

use Carbon\Carbon;
use Modules\Carts\Models\Checkout;
use Modules\Wallet\Exceptions\InsufficientBalance;
use Modules\Carts\Services\Clients\AbstractClientService;

class Wallet extends AbstractClientService
{
    protected $gateway = 'wallet';

    public function process(Checkout $checkout)
    {
        return (object) [
            'url' => route('user.pay.approval', [$checkout->id])
        ];
    }

    public function store($checkout)
    {
        $item = $checkout->lineItems->first();
        $wallet = $checkout->customer->getWalletByCurrency($item->checkoutable->currency);
        if (!$wallet) {
            throw InsufficientBalance::insufficient();
        }

        $total = $checkout->getTotal(false, $wallet->currency);

        if ($total > $wallet->balance) {
            throw InsufficientBalance::insufficient();
        }

        $trial_ends_at = null;
        $payment = null;
        $ends_at = null;
        if ($checkout->hasTrial()) {
            $trial_ends_at = now()->addDays($checkout->getTotalTrialDays())
                ->format('Y-m-d H:i:s');
            $ends_at = $trial_ends_at;
        } else {
            $checkout->customer->unLoadWallet($wallet, $total);
            $payment = [
                'transaction_id' => uniqid('trx-'),
                'currency' => $wallet->currency,
                'total' => $total
            ];

            $ends_at = now()->add(
                $item->checkoutable->term->interval,
                $item->checkoutable->term->interval_count
            )->format('Y-m-d H:i:s');
        }

        $payload = [
            'ref_profile_id' => uniqid('wlt-'),
            'name' => 'main',
            'trial_ends_at' => $trial_ends_at,
            'starts_at' => now()->format('Y-m-d H:i:s'),
            'ends_at' => $ends_at,
            'wallet' => $wallet
        ];

        if ($payment) {
            $payload['payment'] = $payment;
        }

        return $payload;
    }

    public function getToken(array $attributes)
    {
        return $attributes['checkout'];
    }
}
