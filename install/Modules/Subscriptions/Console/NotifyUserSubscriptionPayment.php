<?php

namespace Modules\Subscriptions\Console;

use Illuminate\Console\Command;
use Modules\Cashier\Facades\Cashier;
use Camroncade\Timezone\Facades\Timezone;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Subscriptions\Support\SubscriptionEmailType;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class NotifyUserSubscriptionPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:email-send-upcoming-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to all subscriptions with upcoming payment.';

    protected $mailer;

    protected $subscriptions;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->mailer = new Mailer;
        $this->subscriptions = new SubscriptionsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptions = $this->subscriptions->getUpcomingRecurringFromDays(setting('subscription_payment_incoming_email_days', 1));
        $subscriptions->map(function ($subscription) {
            if ($subscription->getTotal(false)) {
                try {
                    $dueDate = Timezone::convertFromUTC(
                        $subscription->ends_at,
                        ($subscription->subscribable->timezone ?? config('app.timezone')),
                        'Y M, d'
                    );

                    $this->mailer->template(SubscriptionEmailType::INCOMING_INVOICE)
                        ->to($subscription->subscribable->email)
                        ->with([
                            'amount' => $subscription->getTotal(true, $subscription->subscribable->currency),
                            'name' => $subscription->subscribable->full_name,
                            'due_date' => $dueDate,
                            'package' => $subscription->getPackageName(),
                            'gateway_name' => Cashier::client($subscription->gateway)->service->getConfig('title')
                        ])
                        ->send();
                } catch (\Exception $e) {
                    report($e);
                }
            }
        });
    }
}