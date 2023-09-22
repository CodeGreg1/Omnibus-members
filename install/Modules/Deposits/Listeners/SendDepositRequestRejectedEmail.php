<?php

namespace Modules\Deposits\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Deposits\Models\DepositEmailType;

class SendDepositRequestRejectedEmail
{
    public $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mailer = new Mailer;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (
            setting('allow_deposit_rejected_email') === 'enable'
            && $event->deposit->method_id
            && is_null($event->deposit->gateway)
        ) {
            try {
                $this->mailer->template(DepositEmailType::REJECTED)
                    ->to($event->deposit->user->email)
                    ->with([
                        'user' => $event->deposit->user->getName(),
                        'trx' => $event->deposit->trx,
                        'method' => $event->deposit->method->name,
                        'date' => $event->deposit->created_at->toUserTimezone()->toUserFormat(),
                        'amount' => currency_format($event->deposit->amount, $event->deposit->currency),
                        'charge' => currency_format($event->deposit->charge, $event->deposit->currency),
                        'href' => route('user.deposits.histories.index')
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
