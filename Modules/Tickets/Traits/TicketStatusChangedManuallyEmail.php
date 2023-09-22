<?php

namespace Modules\Tickets\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Tickets\Support\TicketStatus;

trait TicketStatusChangedManuallyEmail
{
    public function sendEmailTicketStatusChangedManually() 
    {   
        $logged = auth()->user();

        $this->mailer->template(TICKET_STATUS_CHANGED_MANUALLY_EMAIL_TEMPLATE)
            ->to($this->model->user->email)
            ->with([
                'ticket_category' => (isset($this->model->category->name) ? $this->model->category->name : null),
                'first_name' => $logged->first_name,
                'last_name' => $logged->last_name,
                'ticket_status' => TicketStatus::name(
                    $this->model->status
                ),
                'ticket_number' => $this->model->number,
                'ticket_url' => route(
                    'user.tickets.show',
                    $this->model->number
                )
            ])->send();
    }
}