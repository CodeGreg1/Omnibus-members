<?php

namespace Modules\Cashier\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleCashierGatewaySettingsUpdating
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
        if ($event->settings['gateway'] === 'paypal') {
            cache()->forget('paypal.access_credentials');
        }
    }
}
