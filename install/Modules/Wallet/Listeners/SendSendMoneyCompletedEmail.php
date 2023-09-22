<?php

namespace Modules\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Modules\Wallet\Models\WalletEmailType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Services\Mailer;
use Modules\AvailableCurrencies\Facades\Currency;

class SendSendMoneyCompletedEmail
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
        if (setting('allow_send_money_completed_sender_email') === 'enable') {
            try {
                $transaction = $event->senderWallet->transactions()->latest()->first();

                $this->mailer->template(WalletEmailType::SEND_MONEY)
                    ->to($event->senderWallet->user->email)
                    ->with([
                        'trx' => $transaction->trx,
                        'currency_name' => Currency::getCurrencyProp(
                            $event->senderWallet->currency,
                            'name',
                            $event->senderWallet->currency
                        ),
                        'user' => $event->senderWallet->user->full_name,
                        'amount' => $event->payload['amount_display'],
                        'receiver_name' => $event->receiverWallet->user->full_name,
                        'href' => route('user.profile.wallet.index')
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }

        if (setting('allow_send_money_completed_receiver_email') === 'enable') {
            try {
                $transaction = $event->receiverWallet->transactions()->latest()->first();

                $this->mailer->template(WalletEmailType::RECEIVED_MONEY)
                    ->to($event->receiverWallet->user->email)
                    ->with([
                        'trx' => $transaction->trx,
                        'currency_name' => Currency::getCurrencyProp(
                            $event->receiverWallet->currency,
                            'name',
                            $event->receiverWallet->currency
                        ),
                        'user' => $event->receiverWallet->user->full_name,
                        'amount' => $event->payload['amount_display'],
                        'sender_name' => $event->senderWallet->user->full_name,
                        'href' => route('user.profile.wallet.index')
                    ])
                    ->send();
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
