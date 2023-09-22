<?php

namespace Modules\Carts\Events;

use Modules\Carts\Models\PromoCode;
use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class PromoCodeCreated
{
    use SerializesModels, Dispatchable;

    /**
     * The promo code model instance.
     *
     * @var PromoCode
     */
    public $promoCode;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PromoCode $promoCode)
    {
        $this->promoCode = $promoCode;

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
            $this->promoCode,
            'promo code',
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
