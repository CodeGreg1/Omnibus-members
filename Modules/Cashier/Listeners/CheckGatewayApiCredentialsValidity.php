<?php

namespace Modules\Cashier\Listeners;

use Modules\Cashier\Facades\Cashier;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckGatewayApiCredentialsValidity
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
        $clients = Cashier::getActiveClients();
        // dd($clients);
    }
}
