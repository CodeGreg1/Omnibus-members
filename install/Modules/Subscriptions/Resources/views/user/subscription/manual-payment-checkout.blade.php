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
                    <a href="{{ route('user.subscriptions.index') }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('Back to subscription')
                        </span>
                    </a>
                </div>

                <div class="d-flex flex-column mt-3">
                    <span class="text-gray-500">
                        @lang('Subscription payment for package one')
                    </span>
                    <div class="d-flex align-items-center">
                        <strong class="text-white text-4xl checkout-total">
                            {{ $subscription->getTotal(true, $subscription->item->currency) }}
                        </strong>
                    </div>
                </div>
            </div>
            <form class="checkout-payment" id="subscription-manual-payment-checkout">
                <div id="checkout-form">

                    <header class="column-header mb-2">
                        <span class="column-title text-lg">@lang('Wallet balance')</span>
                    </header>
                    <strong class="text-3xl">
                        {{ currency_format($wallet->balance, $wallet->currency) }}
                    </strong>
                </div>

                <button type="submit" data-route="{{ route('user.subscriptions.manual-payment-process', $subscription) }}" class="btn btn-primary btn-block btn-lg mt-4">
                    {{ __('Confirm payment') }}
                </button>

                <div class="text-muted text-xs text-center mt-2">
                    @lang('By confirming your payment, you allow :app_name to charge your selected payment method for this payment and future payments in accordance with their terms', [
                        'app_name' => setting('app_name')
                    ])
                </div>
            </form>
        </div>
    </div>
@endsection
