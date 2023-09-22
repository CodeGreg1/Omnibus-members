<?php

namespace Modules\Subscriptions\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemovePriceGatewayIntegration
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
        $event->price->gatewayIntegrations->map(function ($integration) {
            $integration->getClient()->api()->prices->delete($integration->id);
            $integration->delete();
        });
    }
}
