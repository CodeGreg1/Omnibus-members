<?php

namespace Modules\Deposits\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Deposits\Models\DepositEmailType;

class SendDepositApprovalEmail
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
            setting('allow_deposit_approval_email') === 'enable'
            && $event->deposit->method_id
            && is_null($event->deposit->gateway)
        ) {
            try {
                $this->mailer->template(DepositEmailType::APPROVAL)
                    ->to(EMAIL_ADMINS)
                    ->with([
                        'user' => $event->deposit->user->getName(),
                        'method' => $event->deposit->method->name,
                        'amount' => currency_format($event->deposit->amount, $event->deposit->currency),
                        'href' => route('admin.deposits.show', $event->deposit)
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
