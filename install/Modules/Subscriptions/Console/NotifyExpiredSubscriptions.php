<?php

namespace Modules\Subscriptions\Console;

use Setting;
use Illuminate\Console\Command;
use Modules\Subscriptions\Models\Subscription;
use Modules\Subscriptions\Events\SubscriptionExpired;

class NotifyExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:notify-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify all expired subscriptions.';

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
        $extension = intval(setting('subscription_expiration_extension'));
        $date = now()->subDays($extension);
        if (!$extension) {
            $date = $date->subHours(12);
        }

        $subscriptions = Subscription::whereNull('canceled_at')
            ->where('expired_notified_email', 0)
            ->where('ends_at', '<', $date)
            ->where(function ($q) {
                $q->where('ended_at', '<', now())
                    ->orWhereNull('ended_at');
            })
            ->get();

        if ($subscriptions->count()) {
            $subscriptions->map(function ($subscription) {
                if ($subscription->gateway !== 'gateway') {
                    $result = $subscription->cancel('now', false);
                    if ($result) {
                        $subscription->fill([
                            'cancels_at_end' => 1
                        ])->save();
                    }
                }

                SubscriptionExpired::dispatch($subscription);
            });
        }
    }
}
