<?php

namespace Modules\Subscriptions\Listeners;

use Modules\Cashier\Facades\Cashier;
use Modules\Cashier\Services\Client;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Subscriptions\Facades\Price;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateGatewayIntegrationFromPrice
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
        $price = $event->price;
        if ($price->price && $price->isRecurring()) {
            collect(Cashier::getActiveClients())->map(function (Client $client) use ($price) {
                $integration = Price::provider($client->key)->create(
                    $client->getConfig('product_key'),
                    $price
                );

                if ($integration) {
                    $price->gatewayIntegrations()->updateOrCreate([
                        'gateway' => $client->key
                    ], [
                        'id' => $integration->id
                    ]);
                }
            });
        }
    }
}
