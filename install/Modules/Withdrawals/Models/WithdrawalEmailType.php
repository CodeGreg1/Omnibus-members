<?php

namespace Modules\Withdrawals\Models;

class WithdrawalEmailType
{
    /**
     * @var string APPROVAL
     */
    const APPROVAL = 'Withdraw approval email';

    /**
     * @var string APPROVED
     */
    const APPROVED = 'Withdraw request approved';

    /**
     * @var string REJECTED
     */
    const REJECTED = 'Withdraw request rejected';

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
            self::REJECTED => self::REJECTED
        ];
    }
}