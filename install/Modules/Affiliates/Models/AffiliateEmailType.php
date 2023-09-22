<?php

namespace Modules\Affiliates\Models;

class AffiliateEmailType
{
    /**
     * @var string AFFILIATE_MEMBERSHIP
     */
    const AFFILIATE_MEMBERSHIP = 'Affiliate membership email';

    /**
     * @var string AFFILIATE_MEMBERSHIP_APPROVED
     */
    const AFFILIATE_MEMBERSHIP_APPROVED = 'Affiliate membership request approved email';

    /**
     * @var string AFFILIATE_MEMBERSHIP_REJECTED
     */
    const AFFILIATE_MEMBERSHIP_REJECTED = 'Affiliate membership request rejected email';

    /**
     * @var string INCOMING_COMMISSION
     */
    const INCOMING_COMMISSION = 'Incoming commission email';

    /**
     * @var string COMMISSION_APPROVED
     */
    const COMMISSION_APPROVED = 'Commission approved email';

    /**
     * @var string COMMISSION_REJECTED
     */
    const COMMISSION_REJECTED = 'Commission rejected email';

    /**
     * Handle on listing wallet exchange email types
     *
     * @return array
     */
    public static function lists()
    {
        return [
            self::AFFILIATE_MEMBERSHIP => self::AFFILIATE_MEMBERSHIP,
            self::AFFILIATE_MEMBERSHIP_APPROVED => self::AFFILIATE_MEMBERSHIP_APPROVED,
            self::AFFILIATE_MEMBERSHIP_REJECTED => self::AFFILIATE_MEMBERSHIP_REJECTED,
            self::INCOMING_COMMISSION => self::INCOMING_COMMISSION,
            self::COMMISSION_APPROVED => self::COMMISSION_APPROVED,
            self::COMMISSION_REJECTED => self::COMMISSION_REJECTED
        ];
    }
}