<?php

namespace Modules\Subscriptions\Services\Service\WebhookHandler;

class WebhookHandler
{
    /**
     * @var WebhookHandlerFactory
     */
    private $webhookHandlerFactory;

    /**
     * Constructor
     *
     * @param WebhookHandlerFactory $webhookHandlerFactory - (Dependency injection) If not provided, a WebhookHandlerFactory instance will be constructed.
     */
    public function __construct(WebhookHandlerFactory $webhookHandlerFactory = null)
    {
        if (null === $webhookHandlerFactory) {
            // Create the service factory
            $webhookHandlerFactory = new WebhookHandlerFactory();
        }
        $this->webhookHandlerFactory = $webhookHandlerFactory;
    }

    /**
     * @param  string $service
     *
     * @return mixed
     */

    public function provider($provider)
    {
        return $this->webhookHandlerFactory->{$provider};
    }
}