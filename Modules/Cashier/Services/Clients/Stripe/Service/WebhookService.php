<?php

namespace Modules\Cashier\Services\Clients\Stripe\Service;

use Modules\Cashier\Services\Clients\Stripe\StripeClient;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\All;
use Modules\Cashier\Services\Clients\Stripe\ApiResources\Create;

class WebhookService extends StripeClient
{
    use Create, All;

    protected $resourceName = 'webhookEndpoints';

    public function delete($id)
    {
        $resource = $this->resourceName;

        try {
            $result = $this->client->{$resource}->delete($id)->toArray();
            return json_decode(json_encode($result), FALSE);
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}