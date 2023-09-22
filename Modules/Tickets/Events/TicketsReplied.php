<?php

namespace Modules\Tickets\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Base\Events\LogActivityEvent;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Tickets\Traits\TicketRepliedEmail;

class TicketsReplied
{
    use SerializesModels, TicketRepliedEmail;

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
        $this->ticket = $this->model->ticket;
        $this->mailer = new Mailer;
        
        // for reply only
        if(is_null($this->model->is_note)) {
            $this->sendEmailTicketReplied();
        }
            
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
        // for reply
        if(is_null($this->model->is_note)) {
            event(new LogActivityEvent(
                $this->ticket,
                'ticket with #' . $this->ticket->number . ' and',
                'replied'
            ));
        } else { //for note
            event(new LogActivityEvent(
                $this->ticket,
                'ticket note with #' . $this->ticket->number . ' and',
                'added'
            ));
        }
    }
}
