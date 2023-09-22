<?php

namespace Modules\Subscriptions\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Payments\Services\Payment;
use Modules\Subscriptions\Facades\Invoice;
use Illuminate\Contracts\Support\Renderable;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Subscriptions\Facades\Subscription;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Subscriptions\Repositories\FeaturesRepository;
use Modules\Subscriptions\Events\SubscriptionPackageChanged;
use Modules\Subscriptions\Events\SubscriptionPaymentCompleted;
use Modules\Subscriptions\Repositories\PackagePricesRepository;
use Modules\Subscriptions\Repositories\SubscriptionsRepository;

class ChangeSubscriptionPackageController extends BaseController
{
    /**
     * @var PackagePricesRepository
     */
    protected $prices;

    /**
     * @var FeaturesRepository
     */
    protected $features;

    /**
     * @var SubscriptionsRepository
     */
    protected $subscriptions;

    public function __construct(
        PackagePricesRepository $prices,
        FeaturesRepository $features,
        SubscriptionsRepository $subscriptions
    ) {
        parent::__construct();

        $this->prices = $prices;
        $this->features = $features;
        $this->subscriptions = $subscriptions;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $subscriptionId
     * @param Request $request
     * @return Renderable
     */
    public function index($subscriptionId, Request $request)
    {
        $price = $request->session()->get('price');
        $subscription = $this->subscriptions->findOrFail($subscriptionId);
        $this->validatePrice($price, $subscription);
        $pricingTableItem = $price->pricingTableItem()->first();

        return view('subscriptions::user.subscription.change-package', [
            'pageTitle' => __('Change subscription package'),
            'subscription' => $subscription,
            'pricingTableId' => $pricingTableItem->pricing_table_id,
            'currenctPrice' => $subscription->item->price,
            'price' => $price,
            'features' => $this->features->orderBy('ordering', 'asc')->all(),
            'subscriptionId' => $subscriptionId
        ]);
    }

    public function create($subscriptionId, $priceId, Request $request)
    {
        $price = $this->prices->findOrFail($priceId);
        $request->session()->put('price', $price);
        return redirect(route('user.subscriptions.change-package.index', [
            'subscription' => $subscriptionId
        ]));
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $subscriptionId
     * @param Request $request
     * @return Renderable
     */
    public function setup($subscriptionId, Request $request)
    {
        $price = $request->session()->get('price');
        $subscription = auth()->user()->latestSubscription;
        $this->validatePrice($price, $subscription);
        $response = Subscription::provider($subscription->gateway)
            ->getCustomerConsentUrl($subscription, $price);

        if ($response) {
            return $this->successResponse('Redirecting to checkout', [
                'redirectTo' => $response
            ]);
        }

        return $this->errorInternalError('Something went wrong on checkout process.');
    }

    public function complete($subscriptionId, Request $request)
    {
        $price = $request->session()->get('price');
        $subscription = auth()->user()->latestSubscription;
        $this->validatePrice($price, $subscription);
        $response = Subscription::provider($subscription->gateway)
            ->completeChangePrice($subscription, $price, $request->all());

        if ($response) {
            $paymentPayload = $response['payment'] ?? null;
            if (isset($response['payment'])) {
                unset($response['payment']);
            }

            if (isset($response['ref_profile_id'])) {
                $newSubscription = auth()->user()->subscriptions()->create($response);

                $newSubscription->item()->create([
                    'package_price_id' => $price->id,
                    'currency' => $price->currency,
                    'total' => $price->price
                ]);
            } else {
                $subscription->update($response);
                $newSubscription = $subscription->fresh();

                $subscription->item->update([
                    'package_price_id' => $price->id,
                    'currency' => $price->currency,
                    'total' => $price->price
                ]);
            }

            if ($newSubscription) {

                if ($paymentPayload) {
                    $payment = Payment::make($newSubscription, array_merge(
                        $paymentPayload,
                        [
                            'gateway' => $newSubscription->gateway,
                        ]
                    ));

                    if ($payment) {
                        PaymentCompleted::dispatch($newSubscription, $payment);
                        SubscriptionPaymentCompleted::dispatch($newSubscription);
                    }
                }

                SubscriptionPackageChanged::dispatch($newSubscription);
            }
        }

        return redirect(route('profile.billing'));
    }

    protected function validatePrice($price, $subscription)
    {
        if (!$price) {
            return redirect(route('profile.billing'));
        }

        if (
            $subscription->id !== intval(request()->subscription)
            && ($price->id === $subscription->item->price->id)
        ) {
            return redirect(route('profile.billing'));
        }
    }
}
