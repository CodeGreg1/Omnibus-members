<?php

namespace Modules\Subscriptions\Listeners;

use Modules\Subscriptions\Facades\WebhookHandler;

class HandleWebhookEvent
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
        $service = WebhookHandler::provider($event->gateway);

        $method = 'handle' . $event->name;
        if (method_exists($service, $method)) {
            $service->{$method}($event->payload);
        }
    }
}