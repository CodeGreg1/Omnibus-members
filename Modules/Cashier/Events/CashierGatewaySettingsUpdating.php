<?php

namespace Modules\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CashierGatewaySettingsUpdating
{
    use SerializesModels, Dispatchable;

    public $settings;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
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
