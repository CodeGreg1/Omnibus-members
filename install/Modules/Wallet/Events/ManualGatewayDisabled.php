<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Illuminate\Foundation\Events\Dispatchable;

class ManualGatewayDisabled
{
    use SerializesModels, Dispatchable;

    /**
     * @var Illuminate\Database\Eloquent\Model $model
     */
    protected $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;

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
            $this->model,
            'manual gateway',
            'disabled'
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
