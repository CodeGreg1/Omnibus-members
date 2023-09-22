<?php

namespace Modules\Carts\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Carts\Models\ShippingRate;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class ShippingRateCreated
{
    use SerializesModels, Dispatchable;

    /**
     * The shipping rate model instance.
     *
     * @var ShippingRate
     */
    public $shippingRate;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ShippingRate $shippingRate)
    {
        $this->shippingRate = $shippingRate;

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
            $this->shippingRate,
            'shipping rate',
            'created'
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
