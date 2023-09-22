<?php

namespace Modules\Cashier\Events;

use Illuminate\Support\Str;
use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class WebhookEventReceived
{
    use SerializesModels, Dispatchable;

    /**
     * the gateway
     */
    public $gateway;

    /**
     * the webhook event name
     */
    public $name;

    /**
     * the webhook payload
     */
    public $payload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gateway, $name, array $payload)
    {
        $this->gateway = $gateway;
        $this->name = $name;
        $this->payload = $payload;

        $this->setLogActivity();
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        $name = ucfirst(str_replace('_', ' ', str()->snake($this->name)));

        event(new LogActivityEvent(
            null,
            'webhook',
            'Received event: ' . $name . ' from ' . $this->gateway
        ));
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}