<?php

namespace Modules\Users\Support;

class UserStatus
{   
    /**
     * @var string ACTIVE
     */
    const ACTIVE = 'Active';

    /**
     * @var string BANNED
     */
    const BANNED = 'Banned';

    /**
     * Handle on listing user status
     * 
     * @return array
     */
    public static function lists()
    {
        return [
            self::ACTIVE => self::ACTIVE,
            self::BANNED => self::BANNED
        ];
    }

    /**
     * Get user status for view
     * 
     * @param \App\Model\User $user
     * 
     * @return string
     */
    public static function view($user) 
    {
        $label = 'badge-success';
        $status = $user->status;

        if(is_null($user->email_verified_at)) {
            $label = 'badge-warning';
            $status = 'Unconfirmed';
        }

        if($user->status != self::ACTIVE) {
            $label = 'badge-danger';
        }

        return '<span class="badge '.$label.'">'.$status.'</span>';
    }
}