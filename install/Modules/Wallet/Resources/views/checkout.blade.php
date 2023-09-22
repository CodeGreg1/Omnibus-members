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
                    <a href="{{ route('user.profile.wallet.index') }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('Back to wallet')
                        </span>
                    </a>
                </div>

                <div class="d-flex flex-column mt-3">
                    <span class="text-white">@lang('Converting')</span>
                    <strong class="text-white text-4xl">{{ currency_format($amount, $currency) }}</strong>
                    <span class="text-gray-500 mt-2">@lang('To')</span>
                    <strong class="text-white text-4xl">{{ currency_format($amount - $total_charge, $target) }}</strong>
                </div>
            </div>
            <div class="checkout-payment">
                <button type="button" class="btn btn-primary btn-block btn-lg mt-4" id="btn-submit-manual-deposit-checkout" data-route="{{ route('user.profile.wallet.exchange.checkout-process') }}">
                    @lang('Convert')
                </button>
            </div>
        </div>
    </div>
@endsection
