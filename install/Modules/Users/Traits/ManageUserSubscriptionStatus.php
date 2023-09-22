<?php

namespace Modules\Users\Traits;

trait ManageUserSubscriptionStatus
{
	public $subscription_trialing = 'trialing';
	public $subscription_active = 'active';
	public $subscription_cancelled = 'cancelled';
	public $subscription_ended = 'ended';
	public $subscription_past_due = 'past_due';

	public function userSubscriptionStatus($query, $status)
	{
		if ($this->subscription_trialing == $status) {
			return $query->isSubscriptionTrailing();
		} elseif ($this->subscription_active == $status) {
			return $query->isSubscriptionActive();
		} elseif ($this->subscription_cancelled == $status) {
			return $query->isSubscriptionCancelled();
		} elseif ($this->subscription_ended == $status) {
			return $query->isSubscriptionEnded();
		} elseif ($this->subscription_past_due == $status) {
			return $query->isSubscriptionPastDue();
		} else {
			return $query->noSubscription();
		}
	}
}