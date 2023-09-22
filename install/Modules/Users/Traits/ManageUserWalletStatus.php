<?php

namespace Modules\Users\Traits;

trait ManageUserWalletStatus
{
    public $has_balance = 'has_balance';
    public $no_balance = 'no_balance';

    public function userWalletStatus($query, $status)
    {
        if ($this->has_balance == $status) {
            return $query->hasBalance();
        } else {
            return $query->noBalance();
        }
    }
}