<?php

namespace Modules\Subscriptions\Repositories;

use Modules\Base\Repositories\BaseRepository;
use Modules\Subscriptions\Models\Subscription;

class SubscriptionsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Subscription::class;

    public function getUpcomingRecurringFromDays($days)
    {
        $date = now()->addDays($days)->format('Y-m-d');
        return $this->getModel()->where('recurring', 1)
            ->whereNull('canceled_at')
            ->whereDate('ends_at', $date)->get();
    }

    /**
     * Get all trialing subscriptions
     *
     * @return Collection
     */
    public function trialing()
    {
        return $this->getModel()
            ->has('subscribable')
            ->where('trial_ends_at', '>=', now())
            ->whereNull('canceled_at')
            ->where('ends_at', '>=', now())
            ->get();
    }

    /**
     * Get all active subscriptions
     *
     * @return Collection
     */
    public function active()
    {
        return $this->getModel()
            ->has('subscribable')
            ->whereNull('canceled_at')
            ->where('ends_at', '>=', now())
            ->get();
    }

    /**
     * Get all ended subscriptions
     *
     * @return Collection
     */
    public function ended()
    {
        return $this->getModel()
            ->has('subscribable')
            ->where('ends_at', '<', now())
            ->get();
    }

    /**
     * Get all subscriptions created today
     *
     * @return Collection
     */
    public function subscribersToday()
    {
        return $this->getModel()
            ->has('subscribable')
            ->whereDate('created_at', now())
            ->get();
    }
}