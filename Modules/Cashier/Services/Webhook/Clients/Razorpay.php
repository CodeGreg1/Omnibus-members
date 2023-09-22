<?php

namespace Modules\Cashier\Services\Webhook\Clients;

use Illuminate\Support\Str;
use Modules\Cashier\Events\WebhookEventReceived;
use Modules\Cashier\Services\Webhook\WebhookServiceClient;
use Modules\Cashier\Services\Contracts\WebhookServiceClientInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Razorpay extends WebhookServiceClient implements WebhookServiceClientInterface
{
    protected $serviceName = 'razorpay';

    /**
     * Verify incoming request from valid gateway.
     *
     * @param Request $request
     * @return bool
     */
    public function verifyIPN($request)
    {
        $expectedSignature = hash_hmac(
            'sha256',
            $request->getContent(),
            $this->api->service->getConfig('webhook_secret')
        );

        $actualSignature = $request->header('X-Razorpay-Signature');

        // Use lang's built-in hash_equals if exists to mitigate timing attacks
        if (function_exists('hash_equals')) {
            $verified = hash_equals($expectedSignature, $actualSignature);
        } else {
            $verified = $this->hashEquals($expectedSignature, $actualSignature);
        }

        if ($verified === false) {
            throw new AccessDeniedHttpException('Invalid signature passed');
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
        $event = Str::studly(str_replace('.', '_', $payload['event']));

        WebhookEventReceived::dispatch($this->serviceName, $event, $payload);

        return [
            'gateway' => $this->serviceName,
            'event' => $event
        ];
    }

    private function hashEquals($expectedSignature, $actualSignature)
    {
        if (strlen($expectedSignature) === strlen($actualSignature)) {
            $res = $expectedSignature ^ $actualSignature;
            $return = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $return |= ord($res[$i]);
            }

            return ($return === 0);
        }

        return false;
    }
}
