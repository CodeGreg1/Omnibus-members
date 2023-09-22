<?php

namespace Modules\Tickets\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait TicketCreatedEmail
{
    public function sendEmailTicketCreated() 
    {
        $this->mailer->template(TICKET_CREATED_EMAIL_TEMPLATE)
            ->to(EMAIL_ADMINS)
            ->with([
                'ticket_category' => (isset($this->model->category->name) ? $this->model->category->name : null),
                'first_name' => $this->model->user->first_name,
                'last_name' => $this->model->user->last_name,
                'ticket_message' => $this->model->message,
                'ticket_number' => $this->model->number,
                'ticket_url' => route(
                    'admin.tickets.show',
                    $this->model->number
                )
            ])->send();
    }
}