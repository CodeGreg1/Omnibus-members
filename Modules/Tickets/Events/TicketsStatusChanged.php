<?php

namespace Modules\Tickets\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Tickets\Traits\TicketStatusChangedManuallyEmail;

class TicketsStatusChanged
{
    use SerializesModels, TicketStatusChangedManuallyEmail;

    /**
     * @var Illuminate\Database\Eloquent\Model $model
     */
    protected $model;

     /**
     * @var Illuminate\Database\Eloquent\Model $ticket
     */
    protected $ticket;

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
        
        $this->sendEmailTicketStatusChangedManually();
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
            'ticket status to ' . $this->model->status . ' with # ' . $this->model->number . ' and',
            'changed'
        ));
    }
}
