<?php

namespace Modules\Tickets\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait TicketRepliedEmail
{
    public function sendEmailTicketReplied() 
    {
        $to = $this->ticket->user->email;
        $route = 'user.tickets.show';

        // to admins if replying with the ticket owner
        if($this->model->user_id == $this->ticket->user_id) {
            $to = EMAIL_ADMINS;
            $route = 'admin.tickets.show';
        }

        $this->mailer->template(TICKET_REPLIED_EMAIL_TEMPLATE)
            ->to($to)
            ->with([
                'ticket_category' => (isset($this->ticket->category->name) ? $this->ticket->category->name : null),
                'first_name' => $this->model->user->first_name,
                'last_name' => $this->model->user->last_name,
                'ticket_reply' => $this->model->message,
                'ticket_number' => $this->ticket->number,
                'ticket_url' => route(
                    $route,
                    $this->ticket->number
                )
            ])->send();
    }
}