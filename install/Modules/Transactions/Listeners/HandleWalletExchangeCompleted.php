<?php

namespace Modules\Transactions\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\MediaLibrary\Conversions\Conversion;

class HandleWalletExchangeCompleted
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
        $fromAmount = currency_format(
            $event->fromAttributes['amount'],
            $event->fromWallet->currency
        );

        $toAmount = currency_format(
            $event->toAttributes['amount'],
            $event->toWallet->currency
        );

        $event->fromWallet->transactions()->create(array_merge([
            'description' => 'Subtracted ' . $fromAmount . ' via ' . $event->fromWallet->currency . ' Wallet via Conversion',
            'user_id' => $event->fromWallet->user_id,
            'currency' => $event->fromWallet->currency,
            'added' => 0,
            'initial_balance' => $event->fromWallet->balance + $event->fromAttributes['amount'],
        ], $event->fromAttributes));

        $event->toWallet->transactions()->create(array_merge([
            'description' => 'Added ' . $toAmount . ' to ' . $event->toWallet->currency . ' Wallet via Conversion',
            'user_id' => $event->toWallet->user_id,
            'currency' => $event->toWallet->currency,
            'added' => 1,
            'initial_balance' => $event->toWallet->balance - $event->toAttributes['amount']
        ], $event->toAttributes));
    }
}
