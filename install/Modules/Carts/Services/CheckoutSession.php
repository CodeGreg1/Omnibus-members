<?php

namespace Modules\Carts\Services;

use Modules\Carts\Models\Checkout;
use Illuminate\Support\Facades\Validator;
use Modules\Carts\Events\CheckoutCompleted;
use Modules\Carts\Contracts\PurchasableItem;
use Modules\Base\Repositories\AddressRepository;
use Modules\Carts\Events\CheckoutOrderCompleted;
use Modules\Carts\Events\CheckoutSessionCreated;
use Modules\Carts\Services\Clients\CheckoutService;
use Modules\Carts\Services\Modes\CheckoutModeService;
use Modules\Carts\Events\CheckoutSubscrptionCompleted;
use Modules\Carts\Exceptions\CheckoutSessionException;

class CheckoutSession
{
    /**
     * Check if customer has items from checkout
     *
     * @param int $customerId
     *
     * @return int
     */
    public static function hasItems($customerId)
    {
        return !!Checkout::where('customer_id', $customerId)->count();
    }

    /**
     * Clear customers checkout items
     *
     * @param int $customerId
     *
     * @return bool
     */
    public static function clearItems($customerId)
    {
        Checkout::where('customer_id', $customerId)->delete();

        return true;
    }

    /**
     * Create checkout resource to storage
     *
     * @param array $payload
     *
     * @return object|null
     */
    public static function create(array $payload)
    {
        self::validateItems($payload['items']);
        $attributes = self::validatePayload($payload);

        $checkout = Checkout::create($attributes);

        if ($checkout) {
            foreach ($payload['items'] as $key => $item) {
                $checkoutItem = $item['item']->checkoutables()->create([
                    'checkout_id' => $checkout->id,
                    'quantity' => $item['quantity']
                ]);

                if (isset($item['tax_rates'])) {
                    $checkoutItem->taxRates()->attach($item['tax_rates']);
                }
            }
        }

        if (!$checkout) {
            return null;
        }

        CheckoutSessionCreated::dispatch($checkout);

        return (object) [
            'url' => route('user.pay.show', $checkout->id),
            'object' => 'checkout',
            'resource' => $checkout
        ];
    }

    /**
     * Retrieve checkout resource from storage
     *
     * @param string|int $id
     *
     * @return Checkout
     */
    public static function retrieve($id)
    {
        $checkout = Checkout::with(['lineItems', 'customer', 'shippingRate', 'promoCode'])->find($id);

        if (!$checkout) {
            throw CheckoutSessionException::checkoutNotFound();
        }

        return $checkout;
    }

    /**
     * Update checkout resource
     *
     * @param Checkout $checkout
     * @param array $fields
     *
     * @return Checkout
     */
    public static function update(Checkout $checkout, $fields)
    {
        return $checkout->update($fields);
    }

    /**
     * Process the checkout
     *
     * @param Checkout $checkout
     * @param array $payload
     *
     * @return null|object
     */
    public static function process(Checkout $checkout, $payload)
    {
        $addresses = new AddressRepository;
        if (isset($payload['shipping_address'])) {
            $address = $addresses->create($payload['shipping_address']);
            $checkout->customer->addresses()->save($address);
            $payload['shipping_address_id'] = $address->id;
        }

        if (isset($payload['billing_address_same'])) {
            $payload['billing_address_id'] = $payload['shipping_address_id'];
        }

        if (isset($payload['billing_address'])) {
            $address = $addresses->create($payload['billing_address']);
            $checkout->customer->addresses()->save($address);
            $payload['billing_address_id'] = $address->id;
        }

        $metadata = [];

        if (isset($payload['note'])) {
            $metadata['note'] = $payload['note'];
        }

        if (isset($payload['phone']) && $payload['phone']) {
            $metadata['phone'] = $payload['phone'];
        }

        $payload['metadata'] = count($metadata) ? $metadata : '{}';

        $checkout->update($payload);
        $checkout = $checkout->fresh();
        $service = new CheckoutService();
        return $service->{$checkout->mode}->{$checkout->gateway}->process($checkout);
    }

    /**
     * Complete the checkout process
     *
     * @param Checkout $checkout
     * @param array $atributes
     *
     * @return null|array
     */
    public static function complete(Checkout $checkout, array $attributes)
    {
        $service = new CheckoutService();
        $attributes['checkout'] = $checkout;
        $token = $service->{$checkout->mode}->{$checkout->gateway}->getToken($attributes);
        $response = $service->{$checkout->mode}->{$checkout->gateway}->store($token);

        if ($response) {
            if ($checkout->mode === 'order') {
                CheckoutOrderCompleted::dispatch($checkout, $response);
            } else {
                CheckoutSubscrptionCompleted::dispatch($checkout, $response);
            }

            CheckoutCompleted::dispatch($checkout);

            return $response;
        }

        return null;
    }

    /**
     * Validate items quantity to stock
     *
     * @param Checkout $checkout
     */
    public static function validateQuantity(Checkout $checkout)
    {
        foreach ($checkout->lineItems as $item) {
            if ($item->checkoutable->isStockable()) {
                $stock = $item->checkoutable->cartStock();
                if ($stock < $item->quantity) {
                    throw CheckoutSessionException::outOfStock($item->checkoutable);
                }
            }
        }
    }

    /**
     * Get breakdown prices
     *
     * @param Checkout $checkout
     * @param bool formatted
     * @return array
     */
    public static function breakdown(Checkout $checkout, $formatted = true)
    {
        return $checkout->getConditionBreakdown($formatted);
    }

    /**
     * Validate items for checkout
     *
     * @param array $items
     */
    protected static function validateItems($items)
    {
        if (!count($items)) {
            throw CheckoutSessionException::noItems();
        }

        $shippable = $items[0]['item']->cartItemShippable();
        foreach ($items as $key => $item) {
            $rules = array(
                'quantity' => ['required', 'numeric']
            );

            if (isset($item['tax_rates'])) {
                $rules['tax_rates'] = ['required', 'array', 'exists:tax_rates,id'];
                $rules['tax_rates.*'] = ['exists:tax_rates,id'];
            }

            $validator = Validator::make($item, $rules);

            if ($validator->fails()) {
                throw CheckoutSessionException::unprocessableAttribute($validator->messages()->first());
            }

            if (!($item['item'] instanceof PurchasableItem)) {
                throw CheckoutSessionException::invalidItem($item['item']->getKey());
            }

            if ($item['item']->cartItemShippable() !== $shippable) {
                throw CheckoutSessionException::unprocessableItems();
            }

            if ($item['item']->isStockable()) {
                $stock = $item['item']->cartStock();
                if ($stock < $item['quantity']) {
                    throw CheckoutSessionException::outOfStock($item['item']);
                }
            }
        }
    }

    /**
     * Validate payload for checkout
     *
     * @param array $payload
     */
    protected static function validatePayload($payload)
    {
        $rules = array(
            'mode' => ['required'],
            'gateway' => ['required'],
            'cancel_url' => ['required'],
            'success_url' => ['required'],
            'customer_id' => ['required', 'exists:users,id']
        );

        if (isset($payload['shipping_address_id'])) {
            $rules['shipping_address_id'] = ['required', 'exists:addresses,id'];
        }

        if (isset($payload['billing_address_id'])) {
            $rules['billing_address_id'] = ['required', 'exists:addresses,id'];
        }

        if (isset($payload['shipping_rate_id'])) {
            $rules['shipping_rate_id'] = ['required', 'exists:shipping_rates,id'];
        }

        if (isset($payload['allow_promo_code'])) {
            $rules['allow_promo_code'] = ['required', 'integer'];
        }

        if (isset($payload['collect_shipping_address'])) {
            $rules['collect_shipping_address'] = ['required', 'integer'];
        }

        if (isset($payload['collect_billing_address'])) {
            $rules['collect_billing_address'] = ['required', 'integer'];
        }

        if (isset($payload['allow_shipping_method'])) {
            $rules['allow_shipping_method'] = ['required', 'integer'];
        }

        if (isset($payload['metadata'])) {
            $rules['metadata'] = ['required', 'json'];
        }

        if (isset($payload['expires_at'])) {
            $rules['expires_at'] = ['required', 'date_format:Y-m-d H:i:s'];
        }

        if (isset($payload['collect_phone_number'])) {
            $rules['collect_phone_number'] = ['required'];
        }

        if (isset($payload['confirm_page_message'])) {
            $rules['confirm_page_message'] = ['required'];
        }

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            throw CheckoutSessionException::unprocessableAttribute($validator->messages()->first());
        }

        return $validator->validated();
    }
}