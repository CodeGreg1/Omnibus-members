<?php

namespace Modules\Deposits\Models;

class DepositEmailType
{
    /**
     * @var string APPROVAL
     */
    const APPROVAL = 'Deposit approval email';

    /**
     * @var string APPROVED
     */
    const APPROVED = 'Deposit request approved';

    /**
     * @var string REJECTED
     */
    const REJECTED = 'Deposit request rejected';

    /**
     * @var string COMPLETED
     */
    const COMPLETED = 'Wallet deposit completed';

    /**
     * Handle on listing deposit email types
     *
     * @return array
     */
    public static function lists()
    {
        return [
            self::APPROVAL => self::APPROVAL,
            self::APPROVED => self::APPROVED,
            self::REJECTED => self::REJECTED,
            self::COMPLETED => self::COMPLETED
        ];
    }
}