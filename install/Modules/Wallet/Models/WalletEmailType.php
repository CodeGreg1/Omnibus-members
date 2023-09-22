<?php

namespace Modules\Wallet\Models;

class WalletEmailType
{
    /**
     * @var string COMPLETED
     */
    const COMPLETED = 'Wallet exchange completed';

    /**
     * @var string SEND_MONEY
     */
    const SEND_MONEY = 'Send money';

    /**
     * @var string RECEIVED_MONEY
     */
    const RECEIVED_MONEY = 'Received money';

    /**
     * @var string OTP
     */
    const OTP = 'One-Time Password';

    /**
     * Handle on listing wallet exchange email types
     *
     * @return array
     */
    public static function lists()
    {
        return [
            self::COMPLETED => self::COMPLETED,
            self::SEND_MONEY => self::SEND_MONEY,
            self::RECEIVED_MONEY => self::RECEIVED_MONEY,
            self::OTP => self::OTP
        ];
    }
}