<?php

namespace Modules\EmailTemplates\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;

class EmailTemplatesCreated
{
    use SerializesModels;

    protected $emailTemplates;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($emailTemplates)
    {
        $this->emailTemplates = $emailTemplates;

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
            $this->emailTemplates,
            'email template',
            'created'
        ));
    }
}
