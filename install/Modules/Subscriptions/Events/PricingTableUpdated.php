<?php

namespace Modules\Subscriptions\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Subscriptions\Models\PricingTable;

class PricingTableUpdated
{
    use SerializesModels, Dispatchable;

    /**
     * The pricing table model instance.
     *
     * @var PricingTable
     */
    public $pricingTable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PricingTable $pricingTable)
    {
        $this->pricingTable = $pricingTable;

        $this->setLogActivity();
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        event(new LogActivityEvent(
            $this->pricingTable,
            'pricing table',
            'updated'
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
