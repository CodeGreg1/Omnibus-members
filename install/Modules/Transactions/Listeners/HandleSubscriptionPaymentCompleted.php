<?php

namespace Modules\Transactions\Listeners;

use Modules\Cashier\Facades\Cashier;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleSubscriptionPaymentCompleted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $item = $event->subscription->item;
        $description = 'Paid ' . currency_format($item->total, $item->currency) . ' for subscription payment via';

        $balance = 0;
        $wallet = $event->subscription->subscribable->getWalletByCurrency($item->currency);
        if ($event->subscription->gateway === 'wallet') {
            if ($wallet) {
                $balance = $wallet->balance + $item->total;
            }

            $description .= ' Wallet';
        } else {
            $client = Cashier::getClient($event->subscription->gateway);
            $description .= ' ' . ($client->getConfig('title') ?? $client->name);
            if ($wallet) {
                $balance = $wallet->balance;
            }
        }

        $event->subscription->transactions()->create([
            'amount' => $item->total,
            'description' => $description,
            'user_id' => $event->subscription->subscribable_id,
            'currency' => $item->currency,
            'initial_balance' => $balance,
            'added' => 0
        ]);
    }
}
