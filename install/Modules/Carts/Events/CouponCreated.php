<?php

namespace Modules\Carts\Events;

use Modules\Carts\Models\Coupon;
use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class CouponCreated
{
    use SerializesModels, Dispatchable;

    /**
     * The coupon model instance.
     *
     * @var Coupon
     */
    public $coupon;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;

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
            $this->coupon,
            'coupon',
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
