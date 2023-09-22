<?php

namespace Modules\Affiliates\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class AffiliateCommissionApproved
{
    use SerializesModels;

    /**
     * @var Illuminate\Database\Eloquent\Model $model
     */
    public $model;

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
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
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
            'affiliate commission',
            'Approved'
        ));
    }
}
