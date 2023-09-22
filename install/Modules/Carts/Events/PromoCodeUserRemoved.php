<?php

namespace Modules\Carts\Events;

use App\Models\User;
use Modules\Carts\Models\PromoCode;
use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class PromoCodeUserRemoved
{
    use SerializesModels, Dispatchable;

    /**
     * The promo code model instance.
     *
     * @var PromoCode
     */
    public $promoCode;

    /**
     * The user model instance.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PromoCode $promoCode, User $user)
    {
        $this->promoCode = $promoCode;
        $this->user = $user;

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
            $this->user,
            'promo code applicable user',
            'removed'
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
