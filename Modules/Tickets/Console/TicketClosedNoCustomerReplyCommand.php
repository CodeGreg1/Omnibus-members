<?php

namespace Modules\Tickets\Console;

use Illuminate\Console\Command;
use Modules\EmailTemplates\Services\Mailer;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Tickets\Repositories\TicketsRepository;
use Modules\Tickets\Traits\TicketStatusChangedAutomaticallyEmail;

class TicketClosedNoCustomerReplyCommand extends Command
{
    use TicketStatusChangedAutomaticallyEmail;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:close-no-customer-reply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ticket auto closed if no reply from the customer.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mailer = new Mailer;
        $repo = new TicketsRepository;

        foreach($repo->getLatestConversation() as $ticket) {
            $latestConversation = $ticket->conversations->first();

            if(!is_null($ticket->user_id) 
                && !is_null($latestConversation) 
                && $ticket->user_id != $latestConversation->user_id) {
                
                if($latestConversation->created_at->diffInHours(now()) > setting('ticket_hours_before_auto_closed')) {
                    
                    $repo->setToClosed($ticket->id);

                    $this->sendEmailTicketStatusChangedAutomatically(
                        $mailer, 
                        $ticket
                    );
                }
            }
        }
    }
}
