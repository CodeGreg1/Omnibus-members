<?php

namespace Modules\Cashier\Services\Webhook\Clients;

use Illuminate\Support\Facades\Storage;
use Modules\Cashier\Events\WebhookEventReceived;
use Modules\Cashier\Services\Webhook\WebhookServiceClient;
use Modules\Cashier\Services\Contracts\WebhookServiceClientInterface;

class Mollie extends WebhookServiceClient implements WebhookServiceClientInterface
{
    protected $serviceName = 'mollie';

    /**
     * Verify incoming request from valid gateway.
     *
     * @param Request $request
     * @return bool
     */
    public function verifyIPN($request)
    {
        return true;
    }

    /**
     * Handle incoming webhook events from gateway
     *
     * @param array $payload
     * @param Request $request
     * @return
     */
    public function handle($payload, $request)
    {
        $event = 'Event';


        WebhookEventReceived::dispatch($this->serviceName, $event, $request->all());

        return [
            'gateway' => $this->serviceName,
            'event' => $event
        ];
    }
}
