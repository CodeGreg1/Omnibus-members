<?php

namespace Modules\AvailableCurrencies\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AvailableCurrencies\Models\AvailableCurrency;
use Modules\Base\Models\Currency;

class AddUserCurrency
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
    public function handle($user)
    {
        $currency = Currency::where('code', $user->currency)->first();

        if ($currency) {
            if (!AvailableCurrency::where('currency_id', $currency->id)->exists()) {
                AvailableCurrency::create([
                    'name' => $currency->name,
                    'symbol' => $currency->symbol,
                    'code' => $currency->code,
                    'exchange_rate' => 1,
                    'status' => 1,
                    'format' => $currency->format,
                    'currency_id' => $currency->id
                ]);
            }
        }
    }
}