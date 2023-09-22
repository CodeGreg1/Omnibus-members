@extends('carts::layouts.checkout')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
@endsection

@section('content')
    <div class="checkout-section">
        <div class="checkout-column">
            <div class="checkout-overview">
                <div class="d-flex align-items-center">
                    <a href="{{ route('user.orders.index') }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('View orders')
                        </span>
                    </a>
                </div>

                <div class="d-flex flex-column mt-3">
                    <span class="text-gray-500">@lang('Paid') {{ setting('app_name') }}</span>
                    <strong class="text-white text-4xl checkout-total">{{ $order->getTotal() }}</strong>
                </div>

                <ul class="Checkout-OrderDetails-items">
                    @foreach ($order->items as $item)
                        <div class="OrderDetails-item">
                            <div class="Checkout-LineItem d-flex align-items-start">
                                <div class="Checkout-LineItem-imageContainer mr-3">
                                    <img src="{{ $item->orderable->cartImage() }}" alt="" class="Checkout-LineItem-image">
                                </div>
                                <div class="flex-item width-grow">
                                    <div class="d-flex justify-content-between align-items-baseline wrap-wrap">
                                        <div class="flex-column-break" style="order: 2;"></div>
                                        <div class="flex-column-break" style="order: 6;"></div>
                                        <div class="flex-item w-100 flex-container justify-content-between">
                                            <div class="Checkout-LineItem-productName flex-item width-auto" style="order: 0;">
                                                <span class="Text text-white fw-bolder">
                                                    {{ $item->orderable->cartItemName() }}
                                                </span>
                                            </div>
                                            <div class="ml2 flex-item width-fixed" style="order: 1;">
                                                <span class="m-0 text-white fw-bolder">
                                                    <span>{{ $item->getTotal() }}</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-item width-auto flex-item-align-left lh-lg" style="order: 4;">
                                            <span class="m-0 text-gray-500 text-sm">
                                                {{ $item->orderable->cartItemDescription() }}
                                            </span>
                                        </div>
                                        <div class="flex-item w-100 align-items-end text-right" style="order: 5;">
                                            @if ($item->quantity > 1)
                                                <span class="m-0 text-gray-500 text-sm">
                                                <span>{{ $item->getUnitPrice() }}</span> @lang('each')</span>
                                            @endif

                                        </div>
                                        <div class="Checkout-LineItem-tierBreakdown flex-item width-auto align-items-start text-left" style="order: 7;">
                                            <div class="flex-container"></div>
                                        </div>
                                        <div class="flex-item width-auto" style="order: 3;">
                                            <span class="m-0 text-gray-500 text-sm">Qty {{ real_number($item->quantity) }}<span class="mr-1">, </span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>

                <div class="Checkout-OrderDetails-footer">
                    <div class="Checkout-OrderDetailsFooter-subtotal flex-container justify-content-between">
                        <span class="m-0 text-white fw-bolder">@lang('Subtotal')</span>
                        <span class="m-0 text-white fw-bolder" id="Checkout-SubtotalAmount">
                            <span class="checkout-subtotal">{{ $order->getSubtotal() }}</span>
                        </span>
                    </div>
                    <div class="Checkout-OrderDetailsFooter-subtotalItems">
                        @if ($order->hasDiscount())
                            <div class="mb-3 flex-container justify-content-between">
                                <div>
                                    <div class="flex-container">
                                        <span class="m-0 text-gray-500 checkout-tax-rate-title">
                                            @lang('Discount')
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-container" style="opacity: 1;">
                                    <span class="m-0 text-gray-500">
                                        <span class="checkout-tax-rate-amount">
                                            {{ $order->getTotalDiscount() }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if ($order->shipping_amount)
                            <div class="mb-3 flex-container justify-content-between">
                                <div>
                                    <div class="flex-container">
                                        <span class="m-0 text-gray-500 checkout-tax-rate-title">
                                            @lang('Total shipping')
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-container" style="opacity: 1;">
                                    <span class="m-0 text-gray-500">
                                        <span class="checkout-tax-rate-amount">
                                            {{ $order->getShippingAmount() }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if ($order->total_tax)
                            <div class="mb-3 flex-container justify-content-between">
                                <div>
                                    <div class="flex-container">
                                        <span class="m-0 text-gray-500 checkout-tax-rate-title">
                                            @lang('Total tax')
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-container" style="opacity: 1;">
                                    <span class="m-0 text-gray-500">
                                        <span class="checkout-tax-rate-amount">
                                            {{ $order->getTotalTax() }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="Checkout-OrderDetails-total flex-container justify-content-between">
                        <span>
                            <span class="m-0 text-white fw-bolder">@lang('Total paid')</span>
                        </span>
                        <span class="m-0 text-white fw-bolder" id="Checkout-TotalAmount">
                            <span class="checkout-total">{{ $order->getTotal() }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="checkout-payment">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-check-circle text-success text-3xl mb-3"></i>
                    <strong class="text-lg">
                         @lang('Thanks for your order')
                    </strong>
                    <p class="text-sm text-muted text-center mt-2 lh-base">
                        @lang('A payment to :gateway will appear on your statement.', [
                            'gateway' => $order->gateway
                        ])
                    </p>
                </div>

                <div class="checkout-confirm-payment-details">
                    <div class="checkout-confirm-payment-gateway">
                        {{ $order->gateway }}
                    </div>
                    <div class="checkout-confirm-payment-total">
                        {{ $order->getTotal() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
