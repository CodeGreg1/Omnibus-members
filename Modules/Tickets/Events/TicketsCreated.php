<?php

namespace Modules\Tickets\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Tickets\Traits\TicketCreatedEmail;

class TicketsCreated
{
    use SerializesModels, TicketCreatedEmail;

    /**
     * @var Illuminate\Database\Eloquent\Model $model
     */
    protected $model;

    /**
     * @var Mailer $mailer
     */
    protected $mailer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->mailer = new Mailer;
        
        $this->sendEmailTicketCreated();
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
        $this->model->flushQueryCache();
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
            'ticket',
            'created'
        ));
    }
}
