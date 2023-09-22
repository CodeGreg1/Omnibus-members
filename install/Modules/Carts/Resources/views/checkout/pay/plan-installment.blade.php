@extends('carts::layouts.checkout')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
@endsection

@section('content')
    @include('partials.messages')
    <div class="checkout-section">
        <div class="checkout-column">
            <div class="checkout-overview">
                <div class="d-flex align-items-center">
                    <a href="{{ $checkout->cancel_url }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('Back to loan details')
                        </span>
                    </a>
                </div>

                <div class="d-flex flex-column mt-3">
                    <span class="text-gray-500">@lang('Pay') {{ setting('app_name') }}</span>
                    <strong class="text-white text-4xl checkout-total">{{ $breakdown['total'] }}</strong>
                </div>

                <ul class="Checkout-OrderDetails-items">
                    @foreach ($checkout->lineItems as $item)
                        <div class="OrderDetails-item">
                            <div class="Checkout-LineItem d-flex align-items-start">
                                @if ($item->checkoutable->cartImage())
                                    <div class="Checkout-LineItem-imageContainer mr-3">
                                        <img src="{{ $item->checkoutable->cartImage() }}" alt="" class="Checkout-LineItem-image">
                                    </div>
                                @endif
                                <div class="flex-item width-grow">
                                    <div class="d-flex justify-content-between align-items-baseline wrap-wrap">
                                        <div class="flex-column-break" style="order: 2;"></div>
                                        <div class="flex-column-break" style="order: 6;"></div>
                                        <div class="flex-item w-100 flex-container justify-content-between">
                                            <div class="Checkout-LineItem-productName flex-item width-auto" style="order: 0;">
                                                <span class="Text text-white fw-bolder">
                                                    {{ $item->checkoutable->cartItemName() }}
                                                </span>
                                            </div>
                                            <div class="ml2 flex-item width-fixed" style="order: 1;">
                                                <span class="m-0 text-white fw-bolder">
                                                    <span>{{ $item->getTotalPrice() }}</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-item width-auto flex-item-align-left lh-lg" style="order: 4;">
                                            <span class="m-0 text-gray-500 text-sm">
                                                {{ $item->checkoutable->cartItemDescription() }}
                                            </span>
                                        </div>
                                        <div class="flex-item w-100 align-items-end text-right" style="order: 5;">
                                            @if ($item->quantity > 1)
                                                <span class="m-0 text-gray-500 text-sm">
                                                <span>{{ $item->getPrice() }}</span> @lang('each')</span>
                                            @endif

                                        </div>
                                        <div class="Checkout-LineItem-tierBreakdown flex-item width-auto align-items-start text-left" style="order: 7;">
                                            <div class="flex-container"></div>
                                        </div>
                                        <div class="flex-item width-auto" style="order: 3;">
                                            <span class="m-0 text-gray-500 text-sm">Qty {{ $item->quantity }}<span class="mr-1">, </span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>

                <div class="Checkout-OrderDetails-footer Items-no-margin-left">
                    <div class="Checkout-OrderDetailsFooter-subtotal flex-container justify-content-between">
                        <span class="m-0 text-white fw-bolder">@lang('Subtotal')</span>
                        <span class="m-0 text-white fw-bolder" id="Checkout-SubtotalAmount">
                            <span class="checkout-subtotal">{{ $breakdown['subtotal'] }}</span>
                        </span>
                    </div>

                    @if ($checkout->allow_promo_code || $checkout->allow_shipping_method || (isset($breakdown['taxRates']) && count(isset($breakdown['taxRates']))))
                        <div class="Checkout-OrderDetailsFooter-subtotalItems">
                            @if ($checkout->allow_promo_code)
                                <div class="d-flex justify-content-between mb-3 text-white">
                                    <div class="checkout-discount-row mb-2 {{ isset($breakdown['discount']) ? '' : 'd-none' }}">
                                        <div class="d-flex flex-column mr-3 flex-grow-1">
                                            <span class="text-gray-500 checkout-discount-title">
                                                @if (isset($breakdown['discount']))
                                                    {{ $breakdown['discount']['title'] }}
                                                @endif
                                            </span>
                                            <div class="d-flex">
                                                <p class="checkout-discount-description mb-0 text-sm text-gray-500">
                                                    @if (isset($breakdown['discount']))
                                                        {{ $breakdown['discount']['description'] }}
                                                    @endif
                                                </p>
                                                <a href="#" class="checkout-remove-coupon">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <strong class="checkout-discount-amount">
                                            @if (isset($breakdown['discount']))
                                                {{ $breakdown['discount']['amount'] }}
                                            @endif
                                        </strong>
                                    </div>
                                    <form class="w-100 {{ isset($breakdown['discount']) ? 'd-none' : '' }}" id="checkout-coupon-form">
                                        <div class="checkout-coupon-input-group active">
                                            <input type="search" id="checkout_coupon" name="checkout_coupon" class="checkout-coupon-control" placeholder="Promo code">
                                            <button
                                                type="button"
                                                class="checkout-coupon-action"
                                            >
                                                @lang('Apply')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if ($checkout->allow_shipping_method)
                                <div>
                                    <div class="mb-3 flex-container justify-content-between">
                                        <div>
                                            <div class="flex-container">
                                                <span class="m-0 text-gray-500">@lang('Shipping')</span>
                                            </div>
                                            <div class="flex-container">
                                                <span class="m-0 text-gray-500 checkout-shipping-rate-title">
                                                    @if (isset($breakdown['shippingRate']))
                                                        {{ $breakdown['shippingRate']['title'] }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-container" style="opacity: 1;">
                                            <span class="m-0 text-gray-500 checkout-shipping-rate-amount">
                                                @if (isset($breakdown['shippingRate']))
                                                    {{ $breakdown['shippingRate']['price'] }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (isset($breakdown['taxRates']))
                                @foreach ($breakdown['taxRates'] as $title => $value)
                                    <div data-name="{{ $title }}">
                                        <div class="mb-3 flex-container justify-content-between">
                                            <div>
                                                <div class="flex-container">
                                                    <span class="m-0 text-gray-500 checkout-tax-rate-title">
                                                        {{ $title }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-container" style="opacity: 1;">
                                                <span class="m-0 text-gray-500">
                                                    <span class="checkout-tax-rate-amount">
                                                        {{ $value }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <div class="Checkout-OrderDetails-total flex-container justify-content-between">
                        <span>
                            <span class="m-0 text-white fw-bolder">@lang('Total due')</span>
                        </span>
                        <span class="m-0 text-white fw-bolder" id="Checkout-TotalAmount">
                            <span class="checkout-total">{{ $breakdown['total'] }}</span>
                        </span>
                    </div>

                    @if ($hasTrial)
                        <div class="Checkout-OrderDetailsFooter-subtotalItems border-0 mb-0">
                            <p class="text-gray-500 text-sm lh-base mb-1">
                                @lang('Will be collected on :date if not cancelled', [
                                    'date' => $checkout->getStartDate()->isoFormat('MMM D, YYYY')
                                ])
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="checkout-payment">
                <div id="checkout-form">
                    <header class="column-header mb-2">
                        <span class="column-title text-lg">@lang('Contact information')</span>
                    </header>

                    <div class="border rounded mb-3">
                        <div class="d-flex align-items-center px-3 py-2">
                            <i class="fas fa-envelope text-muted mr-2"></i>
                            <strong>{{ $checkout->customer->full_name }}</strong>
                            <span class="mx-2">&#8226;</span>
                            <span class="mr-2">{{ $checkout->customer->email }}</span>
                            <i class="fas fa-check-circle text-success ml-auto"></i>
                        </div>
                        @if ($checkout->collect_phone_number)
                            <div class="d-flex align-items-center px-3 py-2 border-top">
                                <i class="fas fa-mobile text-muted mr-2"></i>
                                <input type="text" class="checkout-input-no-border" id="phone" name="phone" placeholder="Phone (300) 444-5550">
                            </div>
                        @endif
                    </div>

                    @if ($checkout->collect_billing_address)
                        @if ($checkout->collect_shipping_address)

                            @include('carts::checkout.partials.shipping-address-item')

                            <div class="custom-control custom-checkbox mt-4 mb-2">
                                <input type="checkbox" class="custom-control-input" id="billing_address_same" name="billing_address_same" @checked($checkout->shipping_address_id === $checkout->billing_address_id)>
                                <label class="custom-control-label fw-bold" for="billing_address_same">
                                    @lang('Billing address is same as shipping')
                                </label>
                            </div>

                            @include('carts::checkout.partials.billing-address-item')

                        @else
                            @include('carts::checkout.partials.billing-address-item')
                        @endif
                    @endif

                    <div class="form-group mt-4">
                        <label>@lang('Note') <span class="text-muted text-sm">(@lang('Optional'))</span></label>
                        <textarea class="form-control autosize" name="note" id="note">{{ $checkout->getMetadata('note') }}</textarea>
                    </div>

                    @if ($checkout->allow_shipping_method && count($shippingRates))
                        <header class="column-header mt-4">
                            <span class="column-title">@lang('Shipping method')</span>
                            <div class="mt-1 text-sm">
                                @lang('Select your shipping method')
                            </div>
                        </header>
                        <div class="mt-1 mb-3">
                            @foreach ($shippingRates as $key => $shippingMethod)
                            <div class="mb-2 list-select-control">
                                <input type="radio" id="shipping-rate-{{ $shippingMethod->id }}" name="shipping_rate_id" class="list-select-control-input" value="{{ $shippingMethod->id }}" @checked($shippingMethod->id === $checkout->shipping_rate_id)>
                                <label class="list-select-control-label" for="shipping-rate-{{ $shippingMethod->id }}">
                                    <strong class="text-dark">
                                        {{ $shippingMethod->title }}
                                    </strong>
                                    <span class="d-block text-sm">{{ $shippingMethod->price_display }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    <header class="column-header mt-4">
                        <span class="column-title">@lang('Payment method')</span>
                        <div class="mt-1 text-sm">
                            @lang('Select your payment method and proceed to payment')
                        </div>
                    </header>
                    <div class="mt-1 mb-3">
                        @php
                            $selectedGateway = collect($paymentMethods)->first(function ($value, $key) use ($checkout) {
                                return $value->key === $checkout->gateway;
                            });

                            $selectedGateway = $selectedGateway ? $selectedGateway : collect($paymentMethods)->first();
                        @endphp
                        @foreach ($paymentMethods as $key => $paymentMethod)
                            <div class="mb-2 list-select-control">
                                <input type="radio" id="payment_method-{{ $paymentMethod->key }}" name="payment_method" class="list-select-control-input" value="{{ $paymentMethod->key }}" @checked($selectedGateway->key === $paymentMethod->key)>
                                <label class="list-select-control-label" for="payment_method-{{ $paymentMethod->key }}">
                                    <strong class="text-dark">
                                        {{ $paymentMethod->getConfig('name') ?? $paymentMethod->name }}
                                    </strong>
                                </label>
                            </div>
                        @endforeach

                        @if (isset($wallet))
                            @php
                                $walletBalance = $wallet ? $wallet->balance : 0;
                            @endphp
                            @if ($walletBalance >= $lineItem->getTotalPrice(false))
                                <div class="mb-2 list-select-control">
                                    <input type="radio" id="payment_method-wallet" name="payment_method" class="list-select-control-input" value="wallet" @checked($checkout->gateway === 'wallet')>
                                    <label class="list-select-control-label" for="payment_method-wallet">
                                        <strong class="text-dark">
                                            Wallet Balance: {{ currency_format($wallet->balance, $wallet->currency) }}
                                        </strong>
                                    </label>
                                </div>
                            @else
                                <div class="mb-2 list-select-control disabled">
                                    <input type="radio" name="payment_method" class="list-select-control-input" @disabled(true)>
                                    <label class="list-select-control-label">
                                        <strong class="text-dark">
                                            Wallet Balance: {{ currency_format($walletBalance, $checkout->currency) }}
                                        </strong>
                                    </label>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-block btn-lg mt-4" id="btn-submit-checkout">
                    @lang('Pay now')
                </button>
                <div class="text-muted text-xs text-center mt-2">
                    @lang('By confirming your payment, you allow :app_name to charge your selected payment method for this payment and future payments in accordance with their terms', [
                        'app_name' => setting('app_name')
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
