<?php

namespace Modules\Subscriptions\View\Composers;

use Illuminate\View\View;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class SubscriptionWidgetComposer
{
    /**
     * @var SubscriptionsRepository
     */
    public $subscriptions;

    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct(SubscriptionsRepository $subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (file_exists(base_path('.env')) && auth()->check() && auth()->user()->isAdmin()) {
            $widgets = collect([
                [
                    'key' => 'trialing',
                    'label' => 'Trialing Subscriptions',
                    'total' => $this->subscriptions->trialing()->count(),
                    'color' => 'secondary',
                    'icon' => 'fas fa-archive'
                ],
                [
                    'key' => 'active',
                    'label' => 'Active Subscriptions',
                    'total' => $this->subscriptions->active()->count(),
                    'color' => 'primary',
                    'icon' => 'fas fa-check'
                ],
                [
                    'key' => 'subscribers_today',
                    'label' => 'Subscriptions Today',
                    'total' => $this->subscriptions->subscribersToday()->count(),
                    'color' => 'info',
                    'icon' => 'fas fa-info'
                ],
                [
                    'key' => 'total',
                    'label' => 'Total Subscriptions',
                    'total' => $this->subscriptions->count(),
                    'color' => 'success',
                    'icon' => 'fas fa-plus'
                ]
            ]);

            $view->with('subscription_widgets', $widgets);
        }
    }
}
