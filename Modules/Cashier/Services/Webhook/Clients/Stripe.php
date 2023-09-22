<?php

namespace Modules\Cashier\Services\Webhook\Clients;

use Illuminate\Support\Str;
use Stripe\WebhookSignature;
use Modules\Cashier\Events\WebhookEventReceived;
use Stripe\Exception\SignatureVerificationException;
use Modules\Cashier\Services\Webhook\WebhookServiceClient;
use Modules\Cashier\Services\Contracts\WebhookServiceClientInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Stripe extends WebhookServiceClient implements WebhookServiceClientInterface
{
    protected $serviceName = 'stripe';

    /**
     * Install webhook events to gateway.
     *
     * @return mixed
     */
    public function install()
    {
        $url = route('cashier.webhook.handle', ['gateway' => $this->api->service->key]);

        $webhooks = $this->api->webhooks->all();
        if ($webhooks && isset($webhooks->data) && count($webhooks->data)) {
            collect($webhooks->data)->filter(function ($item) use ($url) {
                return $item->url === $url;
            })->map(function ($item) {
                $this->api->webhooks->delete($item->id);
            });
        }

        $response = $this->api->webhooks->create([
            'url' => route('cashier.webhook.handle', ['gateway' => $this->api->service->key]),
            'enabled_events' => $this->api->service->getConfig('webhook_events')
        ]);

        if ($response && isset($response->secret)) {
            $key = setting('cashier_mode', 'sandbox') . '_stripe_webhook_secret';
            return (object) [
                'title' => 'Webhook signing secret',
                'key' => $key,
                'value' => $response->secret,
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
        try {
            WebhookSignature::verifyHeader(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                $this->api->service->getConfig('webhook')['secret']
            );
        } catch (SignatureVerificationException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage(), $exception);
        }

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
        $event = Str::studly(str_replace('.', '_', $payload['type']));

        WebhookEventReceived::dispatch($this->serviceName, $event, $payload);

        return [
            'gateway' => $this->serviceName,
            'event' => $event
        ];
    }
}