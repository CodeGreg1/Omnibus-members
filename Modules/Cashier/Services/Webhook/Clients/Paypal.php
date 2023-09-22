<?php

namespace Modules\Cashier\Services\Webhook\Clients;

use Exception;
use Illuminate\Support\Str;
use Modules\Cashier\Events\WebhookEventReceived;
use Modules\Cashier\Services\Webhook\WebhookServiceClient;
use Modules\Cashier\Services\Contracts\WebhookServiceClientInterface;

class Paypal extends WebhookServiceClient implements WebhookServiceClientInterface
{
    protected $serviceName = 'paypal';

    /**
     * Install webhook events to gateway.
     *
     * @return mixed
     */
    public function install()
    {
        $url = route('cashier.webhook.handle', ['gateway' => $this->api->service->key]);

        $webhooks = $this->api->webhooks->all();
        if ($webhooks && isset($webhooks->webhooks) && count($webhooks->webhooks)) {
            $webhook = collect($webhooks->webhooks)->first(function ($item) use ($url) {
                return $item->url === $url;
            });
            if ($webhook) {
                $this->api->webhooks->delete($webhook->id);
            }
        }

        $response = $this->api->webhooks->create([
            'url' => route('cashier.webhook.handle', ['gateway' => $this->api->service->key]),
            'events' => $this->api->service->getConfig('webhook_events')
        ]);

        if ($response && isset($response->id)) {
            $key = setting('cashier_mode', 'sandbox') . '_paypal_webhook_id';
            return (object) [
                'title' => 'Webhook id',
                'key' => $key,
                'value' => $response->id,
                'private' => 1
            ];
        }

        return null;
    }

    /**
     * Verify incoming request from valid gateway.
     *
     * @param Request $request
     * @return bool
     */
    public function verifyIPN($request)
    {
        $response = $this->api->webhooks->verify([
            'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
            'cert_url' => $request->header('PAYPAL-CERT-URL'),
            'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
            'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
            'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
            'webhook_id' => $this->api->service->getConfig('webhook_id'),
            'webhook_event' => json_decode($request->getContent(), true)
        ]);

        if (
            $response
            && isset($response->verification_status) && $response->verification_status === 'SUCCESS'
        ) {
            return true;
        }

        throw new Exception('Signature not match', 401);
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
        $event = Str::studly(str_replace('.', '_', strtolower($payload['event_type'])));

        WebhookEventReceived::dispatch($this->serviceName, $event, $payload);

        return [
            'gateway' => $this->serviceName,
            'event' => $event
        ];
    }
}