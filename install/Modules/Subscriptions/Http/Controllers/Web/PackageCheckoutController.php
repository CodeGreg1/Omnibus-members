<?php

namespace Modules\Subscriptions\Http\Controllers\Web;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cashier\Facades\Cashier;
use Modules\Carts\Services\CheckoutSession;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\FeaturesRepository;
use Modules\Subscriptions\Repositories\PackagePricesRepository;
use Modules\Subscriptions\Repositories\PricingTablesRepository;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class PackageCheckoutController extends BaseController
{
    /**
     * @var PackagePricesRepository
     */
    protected $prices;

    /**
     * @var SubscriptionsRepository
     */
    protected $subscriptions;

    /**
     * @var PricingTablesRepository
     */
    protected $pricingTables;

    /**
     * @var FeaturesRepository
     */
    protected $features;

    public function __construct(
        PackagePricesRepository $prices,
        SubscriptionsRepository $subscriptions,
        PricingTablesRepository $pricingTables,
        FeaturesRepository $features
    ) {
        parent::__construct();

        $this->prices = $prices;
        $this->subscriptions = $subscriptions;
        $this->pricingTables = $pricingTables;
        $this->features = $features;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($tableId, $priceId, Request $request)
    {
        $price = $this->prices->findOrFail($priceId);
        if (!$price->price) {
            return redirect(route('user.packages.checkout-free', [$tableId, $priceId]));
        }

        $mode = $price->isRecurring() ? 'subscription' : 'subscription_onetime';
        $gateways = Cashier::getFromViewInstance('cart', $mode, $price->currency);

        if (!count($gateways)) {
            return redirect()->back();
        }

        if (auth()->user()->isSubscriptionActive()) {
            $subscription = auth()->user()->latestSubscription;
            if ($subscription->gateway !== 'manual') {
                $request->session()->put('price', $price);
                return redirect(route('user.subscriptions.change-package.index', [
                    'subscription' => $subscription->id
                ]));
            }
        }


        $pricingTable = $this->pricingTables->findOrFail($tableId);
        $item = $pricingTable->items()->where('package_price_id', $price->id)->firstOrFail();

        $checkoutItems = collect([$price])->map(function ($item) {
            return [
                'quantity' => 1,
                'item' => $item
            ];
        })->toArray();

        $payload = [
            'mode' => $mode,
            'gateway' => $gateways[0]->key,
            'cancel_url' => route('user.subscriptions.pricings.index'),
            'success_url' => route('profile.billing'),
            'customer_id' => auth()->id(),
            'expires_at' => now()->format('Y-m-d H:m:s'),
            'allow_promo_code' => $item->allow_promo_code,
            'collect_shipping_address' => 0,
            'collect_billing_address' => 0,
            'allow_shipping_method' => 0,
            'collect_phone_number' => 0,
            'confirm_page_message' => $item->confirm_page_message,
            'items' => $checkoutItems
        ];

        $response = CheckoutSession::create($payload);
        if ($response) {
            return redirect($response->url);
        }

        return redirect(route('user.carts.index'))->withErrors([
            'message' => __('Something went wrong while processing checkout.')
        ]);
    }

    public function checkoutFree($tableId, $priceId)
    {
        $pricingTable = $this->pricingTables->findOrFail($tableId);
        $item = $pricingTable->items()
            ->where('package_price_id', $priceId)
            ->firstOrFail();

        if ($item->price->price) {
            return redirect(route('profile.billing'));
        }

        return view('subscriptions::pricing.checkout', [
            'pageTitle' => __('Package checkout'),
            'price' => $item->price,
            'features' => $this->features->orderBy('ordering', 'asc')->all(),
            'return_url' => route('user.packages.confirm-checkout-free', [$tableId, $priceId])
        ]);
    }

    public function confirmCheckoutFree($tableId, $priceId)
    {
        $pricingTable = $this->pricingTables->findOrFail($tableId);
        $item = $pricingTable->items()
            ->where('package_price_id', $priceId)
            ->firstOrFail();

        if ($item->price->price) {
            return redirect(route('profile.billing'));
        }

        $subscription = auth()->user()->latestSubscription;
        if ($subscription && $subscription->active()) {
            $subscription->cancel('now');
        }

        $endsAt = null;
        if ($item->price->isRecurring()) {
            $endsAt = now()->add(
                Str::lower($item->price->term->interval),
                $item->price->term->interval_count
            )->format('Y-m-d H:i:s');
        }

        $newSubscription = auth()->user()->subscriptions()->create([
            'ref_profile_id' => (string) Str::uuid(),
            'name' => 'main',
            'gateway' => 'manual',
            'recurring' => $item->price->isRecurring(),
            'trial_ends_at' => null,
            'starts_at' => now()->format('Y-m-d H:i:s'),
            'ends_at' => $endsAt,
        ]);

        if ($newSubscription) {
            $newSubscription->item()->create([
                'package_price_id' => $item->price->id,
                'currency' => $item->price->currency,
                'total' => $item->price->price
            ]);
        }

        return redirect(route('profile.billing'));
    }
}