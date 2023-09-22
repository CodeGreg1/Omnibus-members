<?php

namespace Modules\Deposits\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\Deposits\Models\DepositEmailType;

class SendDepositRequestApprovedEmail
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
            setting('allow_deposit_approved_email') === 'enable'
            && $event->deposit->method_id
            && is_null($event->deposit->gateway)
        ) {
            try {
                $wallet = $event->deposit->user->getWalletByCurrency($event->deposit->currency);
                $amount = currency_format($event->deposit->amount, $event->deposit->currency);
                $wallet_balance = $amount;
                $wallet_name = $event->deposit->currency . ' Wallet';
                if ($wallet) {
                    $wallet_balance = currency_format(
                        ($wallet->balance + $event->deposit->amount),
                        $event->deposit->currency
                    );
                }

                $this->mailer->template(DepositEmailType::APPROVED)
                    ->to($event->deposit->user->email)
                    ->with([
                        'user' => $event->deposit->user->getName(),
                        'trx' => $event->deposit->trx,
                        'method' => $event->deposit->method->name,
                        'date' => $event->deposit->created_at->toUserTimezone()->toUserFormat(),
                        'amount' => $amount,
                        'charge' => currency_format($event->deposit->charge, $event->deposit->currency),
                        'total' => currency_format(($event->deposit->amount + $event->deposit->charge), $event->deposit->currency),
                        'wallet' => $wallet_name,
                        'wallet_balance' => $wallet_balance,
                        'now' => now()->toUserTimezone()->toUserFormat(),
                        'href' => route('user.profile.wallet.index')
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
