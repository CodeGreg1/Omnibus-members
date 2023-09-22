<?php

namespace Modules\Carts\Services\Clients\SubscriptionOnetime;

use Illuminate\Support\Str;
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

        $checkout->customer->unLoadWallet($wallet, $total);

        $payload = [
            'ref_profile_id' => uniqid('wlt-'),
            'name' => 'main',
            'trial_ends_at' => null,
            'starts_at' => now()->format('Y-m-d H:i:s'),
            'ends_at' => null,
            'wallet' => $wallet,
            'payment' => [
                'transaction_id' => uniqid('trx-'),
                'currency' => $wallet->currency,
                'total' => $total
            ]
        ];

        return $payload;
    }

    public function getToken(array $attributes)
    {
        return $attributes['checkout'];
    }
}
