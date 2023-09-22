<?php

namespace Modules\Carts\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use Modules\Cashier\Facades\Cashier;
use Illuminate\Support\Facades\Session;
use Modules\Carts\Services\CheckoutSession;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Repositories\CountryRepository;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Exceptions\CheckoutSessionException;
use Modules\Carts\Http\Requests\ProcessCheckoutRequest;
use Modules\Carts\Repositories\ShippingRatesRepository;

class CheckoutController extends BaseController
{
    /**
     * Country repository instance
     *
     * @var CountryRepository
     */
    protected $countries;

    /**
     * Shipping Rates repository instance
     *
     * @var ShippingRatesRepository
     */
    protected $shippingRates;


    public function __construct(
        CountryRepository $countries,
        ShippingRatesRepository $shippingRates
    ) {
        parent::__construct();

        $this->countries = $countries;
        $this->shippingRates = $shippingRates;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('carts::index', [
            'pageTitle' => config('carts.name')
        ]);
    }

    /**
     * Display checkout payment page
     *
     * @return Renderable
     */
    public function show($checkoutId)
    {
        $checkout = CheckoutSession::retrieve($checkoutId);
        $breakdown = CheckoutSession::breakdown($checkout);
        $gateways = Cashier::getFromViewInstance(
            'cart',
            $checkout->mode,
            $breakdown['currency']
        );

        $data = [
            'pageTitle' => __('Cart checkout'),
            'checkout' => $checkout,
            'breakdown' => $breakdown,
            'hasTrial' => $checkout->hasTrial(),
            'currency' => Currency::getUserCurrency()
        ];

        if ($checkout->mode === 'subscription' || $checkout->mode === 'subscription_onetime') {
            $data['lineItem'] = $checkout->lineItems()->first();
            $newGateways = [];
            foreach ($gateways as $gateway) {
                if ($gateway->api()->hasCredentials()) {
                    if (isset($gateway->config['subscription_checkout_conditions'])) {
                        $checkCount = count($gateway->config['subscription_checkout_conditions']);
                        foreach ($gateway->config['subscription_checkout_conditions'] as $condition) {
                            if ((new $condition)->handle($checkout)) {
                                $checkCount--;
                            }
                        }

                        if (!$checkCount) {
                            $newGateways[] = $gateway;
                        }
                    } else {
                        $newGateways[] = $gateway;
                    }
                }
            }
            $gateways = $newGateways;

            if (!$data['lineItem']) {
                Session::flash('success', 'Please refresh this page. Something went wrong in checkout process.');
            }
        }

        if (!count($gateways)) {
            $checkout->delete();
            return redirect(route('pricing'));
        }

        $data['paymentMethods'] = $gateways;

        try {
            CheckoutSession::validateQuantity($checkout);
        } catch (CheckoutSessionException $e) {
            return redirect(route('user.carts.index'))->withErrors(['message' => $e->getMessage()]);
        }

        if (
            Module::has('Wallet')
            && setting('allow_wallet_in_subscription') === 'enable'
            && $data['lineItem']
        ) {
            $data['wallet'] = '';
            $wallet = auth()->user()
                ->getWalletByCurrency($data['lineItem']->checkoutable->currency);
            if ($wallet) {
                $data['wallet'] = $wallet;
            }
        }

        if ($checkout->collect_shipping_address || $checkout->collect_billing_address) {
            $data['addresses'] = $checkout->customer->addresses()->with('country')->get();
            $data['countries'] = $this->countries->all();
        }

        if ($checkout->allow_shipping_method) {
            $data['shippingRates'] = $this->shippingRates->active()->get();
        }

        return view('carts::checkout.pay', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param ProcessCheckoutRequest $request
     * @return Renderable
     */
    public function process(ProcessCheckoutRequest $request, $checkoutId)
    {
        $checkout = CheckoutSession::retrieve($checkoutId);
        $response = CheckoutSession::process($checkout, $request->all());

        if ($response) {
            return $this->successResponse(__('Redirecting to checkout.'), [
                'location' => $response->url
            ]);
        }

        return $this->errorInternalError(__('Something went wrong on payment process.'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function approve(Request $request, $checkoutId)
    {
        $checkout = CheckoutSession::retrieve($checkoutId);
        $response = CheckoutSession::complete($checkout, $request->all());

        if ($response) {
            $endPoint = preg_replace('/_/', '-', $checkout->mode);
            return redirect(route('user.pay.confirm-' . $endPoint, $checkoutId));
        }

        return redirect($checkout->cancel_url);
    }

    /**
     * Update a resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function update(Request $request, $checkoutId)
    {
        $fields = $request->fields;
        $checkout = CheckoutSession::retrieve($checkoutId);
        $result = CheckoutSession::update($checkout, $fields);

        if (!$result) {
            foreach ($fields as $key => $value) {
                $fields[$key] = $checkout->{$key};
            }

            return $this->errorResponse(__('Checkout not updated'), [
                'fields' => $fields
            ]);
        }

        $data = [];

        if ($request->has('breakdown')) {
            $data['breakdown'] = CheckoutSession::breakdown($checkout->fresh());
        }

        return $this->successResponse(__('Checkout updated'), $data);
    }
}
