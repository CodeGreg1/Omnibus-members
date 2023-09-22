<?php

namespace Modules\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Modules\Wallet\Models\WalletEmailType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;

class SendWalletExchangeCompletedEmail
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
        if (setting('wallet_exchange_completed_email') === 'enable') {
            try {
                $rate = '1 ' . $event->fromWallet->currency . ' ~ ';
                $rate .= currency(
                    1,
                    $event->fromWallet->currency,
                    $event->toWallet->currency
                ) . ' ' . $event->toWallet->currency;
                $this->mailer->template(WalletEmailType::COMPLETED)
                    ->to($event->fromWallet->user->email)
                    ->with([
                        'user' => $event->fromWallet->user->full_name,
                        'from_wallet' => $event->fromWallet->currency . ' Wallet',
                        'from_amount' => currency_format($event->fromAttributes['amount'], $event->fromWallet->currency),
                        'charge' => currency_format($event->fromAttributes['charge'], $event->fromWallet->currency),
                        'to_wallet' => $event->toWallet->currency . ' Wallet',
                        'to_amount' => currency_format($event->toAttributes['amount'], $event->toWallet->currency),
                        'current_rate' => $rate,
                        'href' => route('user.profile.wallet.index')
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
