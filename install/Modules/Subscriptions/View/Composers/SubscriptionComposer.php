<?php

namespace Modules\Subscriptions\View\Composers;

use Illuminate\View\View;
use Modules\Subscriptions\Repositories\PricingTablesRepository;

class SubscriptionComposer
{
    /**
     * @var PricingTablesRepository
     */
    public $pricingTables;

    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct(PricingTablesRepository $pricingTables)
    {
        $this->pricingTables = $pricingTables;
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (file_exists(base_path('.env')) && $view->__isset('user')) {
            $subscription = $view->user->subscription();
            if ($subscription) {
                $subscription->item->price->package->features = $subscription->item->price->package->features()->get();
                $subscription->completion = $subscription->completion();
                $pricingTable = $this->pricingTables->active();
                $table = $pricingTable ? $pricingTable->table() : collect([]);
                $table = $table->map(function ($item) use ($subscription) {
                    $item->prices = $item->prices->filter(function ($price) use ($subscription) {
                        return $price->package_price_id !== $subscription->package_price_id;
                    });
                    return $item;
                });

                $view->with('subscription', $subscription);
                $view->with('status', $subscription->getStatus());
                $view->with('pricingTable', $table);
            }
        }
    }
}