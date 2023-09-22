<?php

namespace Modules\Subscriptions\Services;

use Illuminate\Support\Str;
use Modules\Subscriptions\Models\Subscription;

class SubscriptionCompletion
{
    /**
     * @var Subscription
     */
    protected $subscription;

    public $duration = 'days';

    public $totalInterval = 0;

    public $completedInterval = 0;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;

        $this->setCompletionInterval();
    }

    protected function setCompletionInterval()
    {
        if ($this->subscription->isRecurring()) {
            $this->totalInterval = $this->subscription->ends_at->diffInDays($this->subscription->starts_at);
            $this->completedInterval = now()->startOfDay()->diffInDays($this->subscription->starts_at->startOfDay());
        }
    }

    public function getDurationTitle()
    {
        return Str::title($this->duration);
    }

    public function getPercentageCompletion()
    {
        if (!$this->totalInterval || ($this->completedInterval > $this->totalInterval)) {
            return 100;
        }

        return intval(($this->completedInterval / $this->totalInterval) * 100);
    }

    public function getRemaining()
    {
        $remaining = $this->totalInterval - $this->completedInterval;
        return $remaining >= 0 ? $remaining : 0;
    }
}