<?php

namespace Modules\Subscriptions\Support;

class SubscriptionEmailType
{
    /**
     * @var string CREATED
     */
    const CREATED = 'Successful subscription notification';

    /**
     * @var string CANCELLED
     */
    const CANCELLED = 'Subscription cancelled notification';

    /**
     * @var string EXPIRED
     */
    const EXPIRED = 'Subscription expired notification';

    /**
     * @var string PAYMENT_COMPLETED
     */
    const PAYMENT_COMPLETED = 'Subscription payment completed notification';

    /**
     * @var string PAYMENT_FAILED
     */
    const PAYMENT_FAILED = 'Subscription payment failed notification';

    /**
     * @var string INVOICE
     */
    const INVOICE = 'Subscription Invoice';

    /**
     * @var string INCOMING_INVOICE
     */
    const INCOMING_INVOICE = 'Subscription upcoming invoice';

    /**
     * @var string PACKAGE_CHANGE
     */
    const PACKAGE_CHANGE = 'Subscription package change';

    /**
     * @var string ADMIN_CREATED
     */
    const ADMIN_CREATED = 'Subscription created admin email';

    /**
     * @var string ADMIN_CANCELLED
     */
    const ADMIN_CANCELLED = 'Subscription cancelled admin email';

    /**
     * @var string ADMIN_EXPIRED
     */
    const ADMIN_EXPIRED = 'Subscription expired admin email';

    /**
     * @var string ADMIN_PAYMENT_COMPLETED
     */
    const ADMIN_PAYMENT_COMPLETED = 'Subscription payment completed admin email';

    /**
     * @var string ADMIN_PAYMENT_FAILED
     */
    const ADMIN_PAYMENT_FAILED = 'Subscription payment failed admin email';

    /**
     * @var string ADMIN_PACKAGE_CHANGED
     */
    const ADMIN_PACKAGE_CHANGED = 'Subscription package change admin email';

    /**
     * Handle on listing user status
     *
     * @return array
     */
    public static function lists()
    {
        return [
            self::CREATED => self::CREATED,
            self::CANCELLED => self::CANCELLED,
            self::EXPIRED => self::EXPIRED,
            self::PAYMENT_COMPLETED => self::PAYMENT_COMPLETED,
            self::PAYMENT_FAILED => self::PAYMENT_FAILED,
            self::INVOICE => self::INVOICE,
            self::INCOMING_INVOICE => self::INCOMING_INVOICE,
            self::PACKAGE_CHANGE => self::PACKAGE_CHANGE,
            self::ADMIN_CREATED => self::ADMIN_CREATED,
            self::ADMIN_CANCELLED => self::ADMIN_CANCELLED,
            self::ADMIN_EXPIRED => self::ADMIN_EXPIRED,
            self::ADMIN_PAYMENT_COMPLETED => self::ADMIN_PAYMENT_COMPLETED,
            self::ADMIN_PAYMENT_FAILED => self::ADMIN_PAYMENT_FAILED,
            self::ADMIN_PACKAGE_CHANGED => self::ADMIN_PACKAGE_CHANGED
        ];
    }
}