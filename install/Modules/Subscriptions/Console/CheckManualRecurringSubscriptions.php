<?php

namespace Modules\Subscriptions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Payments\Services\Payment;
use Modules\Payments\Events\PaymentFailed;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Subscriptions\Models\Subscription;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Subscriptions\Events\SubscriptionPaymentFailed;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;

class CheckManualRecurringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-manual-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check manual recurring subscriptions.';

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
        // 3 hours before subscription ends
        $start = now()->subMinutes(120);
        $end = now();
        $trialingSubscriptions = Subscription::whereIn('gateway', ['wallet'])
            ->whereNotNull('trial_ends_at')
            ->whereNotNull('ends_at')
            ->whereNull('canceled_at')
            ->whereBetween('trial_ends_at', [$start, $end])
            ->whereDoesntHave('payables', function ($query) {
                $query->whereDate('created_at', now()->format('Y-m-d'));
            })
            ->get();

        if ($trialingSubscriptions->count()) {
            $trialingSubscriptions->map(function ($subscription) {
                $item = $subscription->item;
                $wallet = $subscription->subscribable
                    ->getWalletByCurrency($item->currency);
                DB::transaction(function () use ($subscription, $item, $wallet) {
                    $payload = [
                        'transaction_id' => uniqid('trx-'),
                        'currency' => $item->currency,
                        'gateway' => $subscription->gateway,
                        'total' => $item->total
                    ];
                    if ($wallet && ($wallet->balance >= $item->total)) {
                        $ends_at = now()->add(
                            $item->price->term->interval,
                            $item->price->term->interval_count
                        )->format('Y-m-d H:i:s');

                        $subscription->subscribable->unLoadWallet($wallet, $item->total);

                        $payment = Payment::make($subscription, $payload);

                        $subscription->fill([
                            'trial_ends_at' => null,
                            'starts_at' => $subscription->trial_ends_at,
                            'ends_at' => $ends_at
                        ])->save();

                        PaymentCompleted::dispatch($subscription->fresh(), $payment);
                        SubscriptionPaymentCompleted::dispatch($subscription);
                    } else {
                        $payment = Payment::make($subscription, array_merge($payload, [
                            'state' => 'failed'
                        ]));
                        PaymentFailed::dispatch($subscription, $payment);
                        SubscriptionPaymentFailed::dispatch($subscription);
                    }
                }, 3);
            });
        }

        $paidSubscriptions = Subscription::whereIn('gateway', ['wallet'])
            ->whereNull('trial_ends_at')
            ->whereNotNull('ends_at')
            ->whereNull('canceled_at')
            ->whereBetween('ends_at', [$start, $end])
            ->whereDoesntHave('payables', function ($query) {
                $query->whereDate('created_at', now()->format('Y-m-d'));
            })
            ->get();

        if ($paidSubscriptions->count()) {
            $paidSubscriptions->map(function ($subscription) {
                $item = $subscription->item;
                $wallet = $subscription->subscribable
                    ->getWalletByCurrency($item->currency);
                DB::transaction(function () use ($subscription, $item, $wallet) {
                    $payload = [
                        'transaction_id' => uniqid('trx-'),
                        'currency' => $item->currency,
                        'gateway' => $subscription->gateway,
                        'total' => $item->total
                    ];
                    if ($wallet && ($wallet->balance >= $item->total)) {
                        $ends_at = now()->add(
                            $item->price->term->interval,
                            $item->price->term->interval_count
                        )->format('Y-m-d H:i:s');

                        $subscription->subscribable->unLoadWallet($wallet, $item->total);

                        $payment = Payment::make($subscription, $payload);

                        $subscription->fill([
                            'trial_ends_at' => null,
                            'starts_at' => $subscription->ends_at,
                            'ends_at' => $ends_at
                        ])->save();

                        PaymentCompleted::dispatch($subscription->fresh(), $payment);
                        SubscriptionPaymentCompleted::dispatch($subscription);
                    } else {
                        $payment = Payment::make($subscription, array_merge($payload, [
                            'state' => 'failed'
                        ]));

                        $subscription = $subscription->fresh();
                        PaymentFailed::dispatch($subscription, $payment);
                        SubscriptionPaymentFailed::dispatch($subscription);
                    }
                }, 3);
            });
        }
    }
}
