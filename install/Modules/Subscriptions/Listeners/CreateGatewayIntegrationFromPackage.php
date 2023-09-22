<?php

namespace Modules\Subscriptions\Listeners;

use Modules\Cashier\Facades\Cashier;
use Modules\Cashier\Services\Client;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Subscriptions\Facades\Price;
use Modules\Subscriptions\Facades\Product;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateGatewayIntegrationFromPackage
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
        collect(Cashier::getActiveClients())->map(function (Client $client) use ($package) {
            $package->prices->map(function ($price) use ($client) {
                if ($price->price && $price->isRecurring()) {
                    $priceIntegration = null;
                    $productService = Product::provider($client->key);
                    if ($productService) {
                        $productId = $client->getConfig('product_key');
                        if (!$productId) {
                            $productId = $client->api()->getProductKey();
                            if ($productId) {
                                $client->api()->setProductKey($productId);
                                $priceService = Price::provider($client->key);
                                if ($priceService) {
                                    $priceIntegration = $priceService->create($productId, $price);
                                }
                            }
                        }
                    } else {
                        $priceService = Price::provider($client->key);
                        if ($priceService) {
                            $priceIntegration = $priceService->create($price);
                        }
                    }

                    if ($priceIntegration && isset($priceIntegration->id)) {
                        $price->gatewayIntegrations()->updateOrCreate([
                            'gateway' => $client->key
                        ], [
                            'id' => $priceIntegration->id
                        ]);
                    }
                }
            });
        });
    }
}
