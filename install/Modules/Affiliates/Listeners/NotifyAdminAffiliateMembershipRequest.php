<?php

namespace Modules\Affiliates\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Affiliates\Models\AffiliateEmailType;

class NotifyAdminAffiliateMembershipRequest
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
            setting('notify_admin_affiliate_membership_request') === 'enable'
            && !$event->affiliate->approved
        ) {
            try {
                $this->mailer->template(AffiliateEmailType::AFFILIATE_MEMBERSHIP)
                    ->to(EMAIL_ADMINS)
                    ->with([
                        'user' => $event->affiliate->user->getName()
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
