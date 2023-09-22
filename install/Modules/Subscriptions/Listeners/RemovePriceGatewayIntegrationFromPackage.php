<?php

namespace Modules\Subscriptions\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemovePriceGatewayIntegrationFromPackage
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
        $package = $event->package;
        $package->prices->map(function ($price) {
            $price->gatewayIntegrations->map(function ($integration) {
                $integration->getClient()->api()->prices->delete($integration->id);
                $integration->delete();
            });
        });
    }
}
