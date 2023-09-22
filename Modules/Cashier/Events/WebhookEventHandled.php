<?php

namespace Modules\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class WebhookEventHandled
{
    use SerializesModels, Dispatchable;

    /**
     * the gateway
     */
    public $gateway;

    /**
     * the webhook event name
     */
    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gateway, $event)
    {
        $this->gateway = $gateway;
        $this->event = $event;

        $this->setLogActivity();
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        $event = ucfirst(str_replace('_', ' ', str()->snake($this->event)));

        event(new LogActivityEvent(
            null,
            'webhook',
            'Handled event: ' . $event . ' from ' . $this->gateway
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
