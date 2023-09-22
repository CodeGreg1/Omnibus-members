<?php

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Modules\Tickets\Models\Ticket;
use Modules\Tickets\Repositories\TicketsRepository;

const TICKET_CREATED_EMAIL_TEMPLATE = 'Ticket Created';
const TICKET_REPLIED_EMAIL_TEMPLATE = 'Ticket Replied';
const TICKET_STATUS_CHANGED_MANUALLY_EMAIL_TEMPLATE = 'Ticket Status Changed Manually';
const TICKET_STATUS_CHANGED_AUTOMATICALLY_EMAIL_TEMPLATE = 'Ticket Status Changed Automatically';

if (!function_exists('ticket_number')) {
    /**
     * Ticket number generator
     * 
     * @return string|null
     */
    function ticket_number()
    {
        $ticket = new TicketsRepository;

        do {
            $number = random_int(1000000, 9999999);
        } while ($ticket->where("number", "=", $number)->first());
  
        return $number;
    }
}


if (!function_exists('ticket_counter')) {
    /**
     * Ticket counter for admin or user
     * 
     * @return array
     */
    function ticket_counter()
    {
        $result = [];
        $ticket = new TicketsRepository;

        $userId = auth()->user()->isUser() ? auth()->id() : null;

        foreach($ticket->countStatuses($userId) as $key => $status) {
            $result['statuses'][$key]['total_status'] = $status['total_status'];
            $result['statuses'][$key]['status'] = $status['status'];
        }

        $result['trashed'] = $ticket->countTrashed($userId);
        $result['all'] = $ticket->countAll($userId);

        return $result;
    }
}

if (!function_exists('ticket_conversation_name')) {
    /**
     * Ticket agent total response time
     * 
     * @param Ticket $ticket
     * 
     * @return string|null
     */
    function ticket_conversation_name($ticket)
    {
        if($ticket->user_id == auth()->id()) {
            return __('You');
        } else {
            return !is_null($ticket->user) ? $ticket->user->full_name : null;
        }
    }
}

if (!function_exists('ticket_response_time')) {
    /**
     * Ticket agent total response time
     * 
     * @param Ticket $ticket
     * 
     * @return string|null
     */
    function ticket_response_time($ticket)
    {
        $result = [];

        $ticketCreatedAt = $ticket->created_at->toDateTimeString();

        if(!is_null($ticket->conversations)) {

            foreach($ticket->conversations as $conversation) {
                
                $replyCreatedAt = $conversation->created_at->toDateTimeString();

                $start = Carbon::parse($ticketCreatedAt);
                $end = Carbon::parse($replyCreatedAt);

                $result[] = $end->diffInMinutes($start);
            }
            

            return json_encode($result);
        }
    }
}