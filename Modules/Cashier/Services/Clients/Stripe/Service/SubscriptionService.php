<?php

namespace Modules\Cashier\Services\Clients\Stripe\Service;

use Modules\Cashier\Services\Clients\Stripe\StripeClient;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Create;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Update;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Retrieve;

class SubscriptionService extends StripeClient
{
    use Create, Retrieve, Update;

    protected $resourceName = 'subscriptions';

    public function cancel($subscription)
    {
        try {
            $response = $this->client->subscriptions
                ->cancel($subscription->ref_profile_id)
                ->toArray();

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