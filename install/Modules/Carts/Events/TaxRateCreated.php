<?php

namespace Modules\Carts\Events;

use Modules\Carts\Models\TaxRate;
use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class TaxRateCreated
{
    use SerializesModels, Dispatchable;

    /**
     * The tax rate model instance.
     *
     * @var TaxRate
     */
    public $taxRate;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TaxRate $taxRate)
    {
        $this->taxRate = $taxRate;

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
            $this->taxRate,
            'tax rate',
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
