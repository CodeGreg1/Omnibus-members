<?php

namespace Modules\Subscriptions\Listeners;

use Modules\Cashier\Facades\Cashier;
use Modules\Cashier\Services\Client;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Subscriptions\Facades\Price;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPriceGatewayIntegration
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
        if ($price->isRecurring()) {
            collect(Cashier::getActiveClients())->map(
                function (Client $client) use ($price) {
                    $priceIntegration = $price->gatewayIntegrations()
                        ->where('gateway', $client->key)
                        ->first();

                    if ($priceIntegration && isset($priceIntegration->id)) {
                        $client->api()->prices->delete($priceIntegration->id);
                        $integration = Price::provider($priceIntegration->gateway)->create(
                            $client->getConfig('product_key'),
                            $price
                        );

                        if ($integration) {
                            $priceIntegration->id = $integration->id;
                            $priceIntegration->save();
                        }
                    } else {
                        $integration = Price::provider($client->key)->create(
                            $client->getConfig('product_key'),
                            $price
                        );

                        if ($integration && isset($integration->id)) {
                            $price->gatewayIntegrations()->create([
                                'gateway' => $client->key,
                                'id' => $integration->id,
                                'package_price_id' => $price->id
                            ]);
                        }
                    }
                }
            );
        }
    }
}
