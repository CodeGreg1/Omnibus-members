<?php

namespace Modules\Withdrawals\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Withdrawals\Models\WithdrawalEmailType;

class SendWithdrawalApprovalEmail
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
        if (setting('allow_withdraw_approval_email') === 'enable') {
            try {
                $this->mailer->template(WithdrawalEmailType::APPROVAL)
                    ->to(EMAIL_ADMINS)
                    ->with([
                        'trx' => $event->withdraw->trx,
                        'user' => $event->withdraw->user->getName(),
                        'method' => $event->withdraw->method->name,
                        'amount' => currency_format($event->withdraw->amount, $event->withdraw->currency),
                        'href' => route('admin.withdrawals.show', $event->withdraw)
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
