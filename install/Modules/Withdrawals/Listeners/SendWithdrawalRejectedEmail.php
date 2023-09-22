<?php

namespace Modules\Withdrawals\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Withdrawals\Models\WithdrawalEmailType;

class SendWithdrawalRejectedEmail
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
        if (setting('allow_withdraw_rejected_email') === 'enable') {
            try {
                $this->mailer->template(WithdrawalEmailType::REJECTED)
                    ->to($event->withdraw->user->email)
                    ->with([
                        'trx' => $event->withdraw->trx,
                        'user' => $event->withdraw->user->getName(),
                        'method' => $event->withdraw->method->name,
                        'amount' => currency_format($event->withdraw->amount, $event->withdraw->currency),
                        'date' => $event->withdraw->created_at->toUserTimezone()->toUserFormat(),
                        'charge' => currency_format($event->withdraw->charge, $event->withdraw->currency),
                        'total' => currency_format(($event->withdraw->amount - $event->withdraw->charge), $event->withdraw->currency),
                        'href' => route('user.withdrawals.histories.index')
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
