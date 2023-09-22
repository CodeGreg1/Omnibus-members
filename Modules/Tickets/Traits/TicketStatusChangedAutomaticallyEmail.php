<?php

namespace Modules\Tickets\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Tickets\Support\TicketStatus;

trait TicketStatusChangedAutomaticallyEmail
{
    public function sendEmailTicketStatusChangedAutomatically($mailer, $ticket) 
    {

        $mailer->template(TICKET_STATUS_CHANGED_AUTOMATICALLY_EMAIL_TEMPLATE)
            ->to($ticket->user->email)
            ->with([
                'ticket_category' => (isset($ticket->category->name) ? $ticket->category->name : null),
                'ticket_status' => TicketStatus::name(
                    TicketStatus::CLOSED
                ),
                'ticket_number' => $ticket->number,
                'ticket_hours_before_auto_closed' => setting(
                    'ticket_hours_before_auto_closed'
                ),
                'ticket_url' => route(
                    'user.tickets.show',
                    $ticket->number
                )
            ])->send();
    }
}