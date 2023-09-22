<?php

namespace Modules\Carts\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Carts\Services\CheckoutSession;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Carts\Exceptions\CheckoutSessionException;

class ConfirmCheckoutController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a confirmation page for new subscription.
     * @return Renderable
     */
    public function subscription($checkoutId)
    {
        $checkout = CheckoutSession::retrieve($checkoutId);
        if (!$checkout->checkouted) {
            throw CheckoutSessionException::unprocessedCheckout();
        }

        $subscription = $checkout->checkouted;
        $message = $checkout->confirm_page_message;
        $checkout->delete();

        return view('carts::checkout.confirm-subscription', [
            'pageTitle' => __('Confirm subscription'),
            'subscription' => $subscription,
            'message' => $message
        ]);
    }

    /**
     * Display a confirmation page for new order.
     * @return Renderable
     */
    public function order($checkoutId)
    {
        $checkout = CheckoutSession::retrieve($checkoutId);
        $order = $checkout->checkouted;
        $checkout->delete();

        return view('carts::checkout.confirm-order', [
            'pageTitle' => __('Confirm subscription'),
            'order' => $order
        ]);
    }
}