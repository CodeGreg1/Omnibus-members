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
                    <a href="{{ route('profile.billing') }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('View billing')
                        </span>
                    </a>
                </div>
                <div class="d-flex flex-column mt-3">
                    <span class="text-gray-500">
                        @lang('Subscribed to :package', [
                            'package' => $subscription->getPackageName()
                        ])
                    </span>
                    @if ($subscription->onTrial())
                        <div class="d-flex align-items-start flex-column">
                            <strong class="text-white text-4xl checkout-total">
                                {{ $subscription->item->price->trial_days_count }} {{ $subscription->item->price->trial_days_count > 1 ? 'days' : 'day' }} @lang('free')
                            </strong>
                            <span class="text-gray-500 d-block">
                                @if ($subscription->isRecurring())
                                    @lang('Then :total :desc', [
                                        'total' => $subscription->getTotal(),
                                        'desc' => $subscription->item->price->term->getDescription()
                                    ])
                                @endif
                            </span>
                        </div>
                    @else
                        <div class="d-flex align-items-center">
                            <strong class="text-white text-4xl checkout-total">{{ $subscription->getTotal() }}</strong>
                            <div class="d-flex flex-column text-gray-500 ml-2 lh-base">
                                @if ($subscription->isRecurring())
                                    <span>per</span>
                                    <span>{{ $subscription->item->price->term->getTermDiplayLabel() }}</span>
                                @else
                                    @lang('Lifetime')
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div class="Checkout-OrderDetails-footer">
                    @if ($subscription->onTrial())
                        <div class="Checkout-OrderDetailsFooter-subtotal flex-container justify-content-between ml-0">
                            <span class="m-0 text-white fw-bolder">@lang('Total after trial')</span>
                            <span class="m-0 text-white fw-bolder" id="Checkout-SubtotalAmount">
                                <span class="checkout-subtotal">{{ $subscription->getTotal() }}</span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="checkout-payment">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-check-circle text-success text-3xl mb-3"></i>
                    @if ($message)
                        <p class="text-sm text-center mt-2 lh-base">
                            {{ $message }}
                        </p>
                    @else
                        <strong class="text-lg">
                            @lang('Thanks for subcribing')
                        </strong>
                        @if ($subscription->onTrial())
                            <p class="text-sm text-muted text-center mt-2 lh-base">
                                @lang('After your trial, a payment to :gateway will appear on your statement.', [
                                    'gateway' => $subscription->gateway
                                ])
                            </p>
                        @else
                            <p class="text-sm text-muted text-center mt-2 lh-base">
                                @lang('A payment to :gateway will appear on your statement.', [
                                    'gateway' => $subscription->gateway
                                ])
                            </p>
                        @endif
                    @endif
                </div>

                @if (!$message)
                    <div class="checkout-confirm-payment-details">
                        <div class="checkout-confirm-payment-gateway">
                            {{ $subscription->gateway }}
                        </div>
                        <div class="checkout-confirm-payment-total">
                            {{ $subscription->getTotal() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
