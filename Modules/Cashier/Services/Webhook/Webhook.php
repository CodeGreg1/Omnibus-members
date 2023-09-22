<?php

namespace Modules\Cashier\Services\Webhook;

class Webhook
{
    /**
     * @var WebhookFactory
     */
    private $webhookFactory;

    /**
     * Constructor
     *
     * @param WebhookFactory $webhookFactory - (Dependency injection) If not provided, a WebhookFactory instance will be constructed.
     */
    public function __construct(WebhookFactory $webhookFactory = null)
    {
        if (null === $webhookFactory) {
            // Create the service factory
            $webhookFactory = new WebhookFactory();
        }

        $this->webhookFactory = $webhookFactory;
    }

    /**
     * @param  string $service
     *
     * @return \Modules\Cashier\Services\Contracts\WebhookServiceClientInterface
     */

    public function provider($provider)
    {
        try {
            return $this->webhookFactory->{$provider};
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}