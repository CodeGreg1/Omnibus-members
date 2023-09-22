<?php

namespace Modules\Cashier\Services\Clients\Stripe\Service;

use Modules\Cashier\Services\Clients\Stripe\StripeClient;

class CheckoutService extends StripeClient
{
    public function create($payload)
    {
        try {
            $response = $this->client->checkout->sessions->create($payload);

            if (isset($response['id'])) {
                return json_decode(json_encode($response), FALSE);
            }

            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function retrieve($id)
    {
        try {
            $response = $this->client->checkout->sessions->retrieve($id)->toArray();

            if (isset($response['id'])) {
                return json_decode(json_encode($response), FALSE);
            }

            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}