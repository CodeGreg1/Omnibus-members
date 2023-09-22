<?php

namespace Modules\Affiliates\Listeners;

use Illuminate\Support\Str;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Affiliates\Models\AffiliateEmailType;

class NotifyUserIncomingCommission
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
        if (setting('notify_user_incoming_commissions') === 'enable') {
            try {
                $length = strlen($event->model->user->getName());
                $from_user = Str::mask($event->model->user->getName(), '*', 1, $length - 2);
                $this->mailer->template(AffiliateEmailType::INCOMING_COMMISSION)
                    ->to($event->model->affiliate->user->email)
                    ->with([
                        'user' => $event->model->affiliate->user->getName(),
                        'amount' => currency_format($event->model->amount, $event->model->currency),
                        'type' => $event->model->type,
                        'from_user' => $from_user
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
