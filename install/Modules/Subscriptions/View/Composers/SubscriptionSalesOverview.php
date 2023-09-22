<?php

namespace Modules\Subscriptions\View\Composers;

use Illuminate\View\View;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class SubscriptionSalesOverview
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
            $startDate = now()->subDays(29);
            $subscriptions = $this->subscriptions->getModel()
                ->has('subscribable')
                ->where('created_at', '>=', $startDate)
                ->get();

            $overview = $subscriptions
                ->map(function ($subscription) {
                    $subscription->date = $subscription->created_at->format('Y-m-d');
                    return $subscription;
                })
                ->groupBy('date')
                ->map(function ($items) {
                    $total = $items->sum(function ($subscription) {
                        return $subscription->getTotal(false);
                    });

                    return (object) [
                        'date' => $items->first()->date,
                        'count' => $items->count(),
                        'total' => $total
                    ];
                })
                ->values();

            $salesOverviewData = [];
            $salesOverviewLabels = [];
            $date = $startDate;
            $lastDate = now()->addDay();
            $format = $date->format('Y') === $lastDate->format('Y') ? 'M d' : 'M d, y';
            $totalCount = $overview->sum(function ($item) {
                return $item->count;
            });

            $totalRevenue = $overview->sum(function ($item) {
                return $item->total;
            });

            $currency = Currency::getUserCurrency();
            $renewalRevenue = currency_format($subscriptions
                ->filter(function ($subscription, $key) {
                    return $subscription->payables()->count() > 1;
                })
                ->sum(function ($subscription) use ($currency) {
                    return $subscription->payables()->where('state', 'paid')->get()->sortBy('created_at')
                        ->skip(1)
                        ->reduce(function ($carry, $item) use ($currency) {
                            return $carry + currency(
                                $item->total,
                                $item->currency,
                                $currency,
                                false
                            );
                        }, 0);
                }), $currency);

            while ($date->format('Y-m-d') !== $lastDate->format('Y-m-d')) {
                $label = $date->format($format);
                $salesOverviewLabels[] = $label;
                $salesOverviewData[] = $overview->filter(function ($item) use ($date) {
                    return $item->date === $date->format('Y-m-d');
                })->sum(function ($item) {
                    return $item->count;
                });

                $date = $date->addDay();
            }

            $view->with('subscription_sales_overview', [
                'total_count' => $totalCount,
                'amount_display' => currency_format($totalRevenue, Currency::getUserCurrency()),
                'renewal_revenue' => $renewalRevenue,
                'selector' => 'subscription-sales-overview',
                'result' => [
                    'data' => $salesOverviewData,
                    'labels' => $salesOverviewLabels
                ],
                'type' => 'line',
                'label' => __('Total subscriptions')
            ]);
        }
    }
}
