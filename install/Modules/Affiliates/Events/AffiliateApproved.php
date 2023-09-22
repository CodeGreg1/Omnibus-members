<?php

namespace Modules\Affiliates\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class AffiliateApproved
{
    use SerializesModels;

    /**
     * @var Illuminate\Database\Eloquent\Model $affiliate
     */
    public $affiliate;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($affiliate)
    {
        $this->affiliate = $affiliate;

        $this->setLogActivity();
        $this->flushQueryCache();
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
     * Flush query cache
     *
     * @return void
     */
    protected function flushQueryCache()
    {
        $this->affiliate->flushQueryCache();
    }

    /**
     * Created log activity
     *
     * @return mixed
     */
    protected function setLogActivity()
    {
        event(new LogActivityEvent(
            $this->affiliate,
            'affiliate',
            'approved'
        ));
    }
}
