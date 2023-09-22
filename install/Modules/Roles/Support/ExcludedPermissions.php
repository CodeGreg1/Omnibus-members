<?php

namespace Modules\Roles\Support;

class ExcludedPermissions
{
    /**
     * Handle on listing excluded routes/permissions
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            'auth',
            'debugbar',
            'ignition',
            'login',
            'register',
            'password',
            'logout',
            'verification',
            'two-factor',
            'recovery',
            'user-invitation',
            'profile.billing',
            'dashboard.index',
            'user.subscriptions.webhook',
            'user.subscriptions.pricings.index',
            'user.subscriptions.pricings.show',
            'user.subscriptions.coupons.verify',
            'user.subscriptions.checkouts.store',
            'user.subscriptions.checkouts.success',
            'user.subscriptions.checkouts.cancel',
            'user.subscriptions.checkouts.recurring.create',
            'user.subscriptions.checkouts.recurring.store',
            'user.subscriptions.checkouts.onetime.create',
            'user.subscriptions.checkouts.onetime.store',
            'user.subscriptions.module-usages',
            'user.subscriptions.module-usages.insufficient-balance',
            'user.subscriptions.checkouts.onetime.store'
        ];
    }
}