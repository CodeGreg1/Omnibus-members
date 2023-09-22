@extends('carts::layouts.checkout')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
@endsection

@section('content')
    <div class="checkout-section" id="deposit-checkout">
        <input type="hidden" id="method-currency" value="{{ $currency_code }}" />
        <input type="hidden" id="method-min" value="{{ $min_limit }}" />
        <input type="hidden" id="method-max" value="{{ $max_limit }}" />
        <input type="hidden" id="method-fixed-charge" value="{{ $fixed_charge }}" />
        <input type="hidden" id="method-percent-charge" value="{{ $percent_charge }}" />
        <div class="checkout-column">
            <div class="checkout-overview">
                <div class="d-flex align-items-center">
                    <a href="{{ route('user.deposits.index') }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('Back to Deposit')
                        </span>
                    </a>
                </div>

                <div class="form-group mt-3">
                    <label class="d-block" for="amount">@lang('Amount')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select class="form-select form-control form-control-lg" name="currency" aria-label=".form-select-lg example">
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->code }}" @selected($currency->code === $currency_code)>{{ $currency->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="amount" data-decimals="2" class="form-control form-control-lg" placeholder="0.00">
                    </div>
                </div>

                <label for="">
                    @lang('Charges')
                    <em class="text-white charges-desc">({{ $fixed_charge_display }} +
                        {{ $percent_charge ?? 0 }}%)</em>
                </label>
                <div class="Checkout-OrderDetails-footer Items-no-margin-left">
                    <div
                        class="Checkout-OrderDetailsFooter-subtotal flex-container justify-content-between align-items-end mb-0">
                        <div class="d-flex flex-column">
                            <label class="text-sm mb-0">@lang('Fixed')</label>
                            <div class="d-flex">
                                <span
                                    class="m-0 text-white fw-bolder ml-1 fixed-charge-amount-desc">{{ $fixed_charge_display }}</span>
                            </div>
                        </div>
                        <span class="m-0 text-white fw-bolder">
                            <span id="checkout-fixed-charge">{{ $fixed_charge_display }}</span>
                        </span>
                    </div>

                    <div class="Checkout-OrderDetailsFooter-subtotal flex-container justify-content-between align-items-end">
                        <div class="d-flex flex-column">
                            <label class="text-sm mb-0">@lang('Percent')</label>
                            <div class="d-flex">
                                <span class="m-0 text-white fw-bolder">{{ $percent_charge }}%</span>
                            </div>
                        </div>
                        <span class="m-0 text-white fw-bolder">
                            <span id="checkout-percent-charge">{{ currency_format(0, $currency_code) }}</span>
                        </span>
                    </div>

                    <div class="Checkout-OrderDetails-total flex-container justify-content-between">
                        <span>
                            <span class="m-0 text-white fw-bolder">@lang('Total due today')</span>
                        </span>
                        <span class="m-0 text-white fw-bolder">
                            <span class="checkout-total text-lg">{{ currency_format(0, $currency_code) }}</span>
                        </span>
                    </div>
                </div>

            </div>
            <div class="checkout-payment">
                <span class="column-title text-lg font-weight-600 checkout-gateway-label">Gateway</span>
                <form class="d-flex flex-column mt-3 mt-lg-0" id="checkout-form">
                    @if ($gateway->getConfig('logo'))
                        <div class="d-flex align-items-right checkout-gateway-logo ml-25">
                            <div class="logo-container">
                                <img src="{{ $gateway->getConfig('logo') }}" alt="{{ $gateway->name }}">
                            </div>
                        </div>
                    @endif
                    <header class="column-header mb-2">
                        <span class="column-title text-lg font-weight-600">@lang('Range limit')</span>
                    </header>
                    <div class="border rounded mb-3 text-lg">
                        <div class="d-flex align-items-center px-3 py-2">
                            <i class="fas fa-arrow-up mr-3 text-success"></i>
                            <strong>@lang('Max')</strong>
                            <span class="mx-2">&#8226;</span>
                            <span class="mr-2 range-max">{{ currency_format($max_limit, $currency_code) }}</span>
                        </div>
                        <div class="d-flex align-items-center px-3 py-2 border-top">
                            <i class="fas fa-arrow-down mr-3 text-danger"></i>
                            <strong>@lang('Min')</strong>
                            <span class="mx-2">&#8226;</span>
                            <span class="mr-2 range-min">{{ currency_format($min_limit, $currency_code) }}</span>
                        </div>
                    </div>
                </form>

                <button type="button" class="btn btn-primary btn-block btn-lg mt-4 btn-gateway-checkout-uppercase-style" id="btn-submit-automatic-deposit-checkout" data-route="{{ $process_url }}">
                    @lang('Continue with :name', [
                        'name' => $gateway->getConfig('name') ?? $gateway->name
                    ])
                </button>
            </div>
        </div>
    </div>
@endsection
