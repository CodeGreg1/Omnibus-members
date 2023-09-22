<?php

namespace Modules\Carts\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Carts\Facades\Cart;
use Modules\Carts\Services\Checkout;
use Modules\Cashier\Facades\Cashier;
use Modules\Carts\Services\CheckoutSession;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Repositories\CountryRepository;
use Modules\AvailableCurrencies\Facades\Currency;
use Modules\Carts\Repositories\TaxRatesRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Exceptions\CheckoutSessionException;
use Modules\Carts\Repositories\ShippingRatesRepository;
use Modules\Carts\Http\Requests\StoreCartCheckoutRequest;

class CartCheckoutController extends BaseController
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

    /**
     * Tax Rates repository instance
     *
     * @var TaxRatesRepository
     */
    protected $taxRates;


    public function __construct(
        CountryRepository $countries,
        ShippingRatesRepository $shippingRates,
        TaxRatesRepository $taxRates
    ) {
        parent::__construct();

        $this->countries = $countries;
        $this->shippingRates = $shippingRates;
        $this->taxRates = $taxRates;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (CheckoutSession::hasItems(auth()->id())) {
            CheckoutSession::clearItems(auth()->id());
        }

        if ($request->has('items') && $request->items) {
            $itemsToCheckout = explode(',', $request->items);
            $items = Cart::items([
                'items' => $itemsToCheckout
            ]);
        } else {
            $items = Cart::items();
        }

        $taxRates = $this->taxRates->getActive()->pluck('id')->toArray();

        $checkoutItems = $items->map(function ($item) use ($taxRates) {
            return [
                'quantity' => $item->quantity,
                'item' => $item->purchasable,
                'tax_rates' => $taxRates
            ];
        })->toArray();

        $shippingRate = $this->shippingRates->getDefault();

        $payload = [
            'mode' => 'order',
            'gateway' => Cashier::getFromViewInstance('cart', 'order')[0]->key,
            'cancel_url' => route('user.carts.index'),
            'success_url' => route('user.orders.index'),
            'customer_id' => auth()->id(),
            'expires_at' => now()->format('Y-m-d H:m:s'),
            'allow_promo_code' => 1,
            'collect_shipping_address' => 1,
            'collect_billing_address' => 1,
            'allow_shipping_method' => 1,
            'shipping_rate_id' => optional($shippingRate)->id,
            'items' => $checkoutItems
        ];

        $response = false;

        try {
            $response = CheckoutSession::create($payload);
        } catch (CheckoutSessionException $e) {
            return redirect(route('user.carts.index'))->withErrors(['message' => $e->getMessage()]);
        }

        if ($response) {
            return redirect($response->url);
        }

        return redirect(route('user.carts.index'))->withErrors([
            'message' => __('Something went wrong while processing checkout.')
        ]);
    }
}
