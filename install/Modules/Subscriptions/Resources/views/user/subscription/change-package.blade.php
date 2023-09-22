@extends('carts::layouts.checkout')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
@endsection

@section('content')
    <div class="checkout-section">
        <div class="checkout-column text-lg">
            <div class="checkout-overview">
                <div class="d-flex align-items-center">
                    <a href="{{ route('profile.billing') }}" class="checkout-header-brand">
                        <i class="fas fa-arrow-left checkout-header-brand-icon"></i>
                        <span class="checkout-header-brand-title">
                            @lang('Back to profile')
                        </span>
                    </a>
                </div>

                <div class="d-flex flex-column mt-3 text-white">
                    <span class="text-gray-500 mb-3">@lang('Current package')</span>
                    <strong class="text-white">{{ $currenctPrice->package->name }}</strong>

                    <div class="d-flex align-items-center mt-2">
                        <strong class="text-white text-4xl checkout-total">
                            {{ $currenctPrice->getUnitPrice(true, $currenctPrice->currency) }}
                        </strong>
                        <span class="ml-3 text-gray-500">
                            @if ($currenctPrice->isRecurring())
                                {{ $currenctPrice->term->title }}
                            @else
                                @lang('Lifetime')
                            @endif
                        </span>
                    </div>
                    <ul class="list-unstyled">
                        @foreach ($features as $feature)
                            <li class="py-2 d-flex">
                                @if ($currenctPrice->package->features->first(function ($value, $key) use ($feature) {
                                    return $value->id === $feature->id;
                                }))
                                    <span class="text-success d-flex align-items-center justify-content-center">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </span>
                                @else
                                    <span class="text-gray-500 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-circle text-xl"></i>
                                    </span>
                                @endif
                                <span class="ml-3">{{ $feature->title }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="checkout-payment">
                <div class="d-flex flex-column mt-3 mt-lg-0">
                    <span class="text-muted mb-3">@lang('Change to')</span>
                    <strong>{{ $price->package->name }}</strong>

                    <div class="d-flex align-items-center mt-2">
                        <strong class="text-4xl checkout-total">
                            {{ $price->getUnitPrice(true, $price->currency) }}
                        </strong>
                        <span class="ml-3 text-muted">
                            @if ($price->isRecurring())
                                {{ $price->term->title }}
                            @else
                                @lang('Lifetime')
                            @endif
                        </span>
                    </div>
                    <ul class="list-unstyled">
                        @foreach ($features as $feature)
                            <li class="py-2 d-flex">
                                @if ($price->package->features->first(function ($value, $key) use ($feature) {
                                    return $value->id === $feature->id;
                                }))
                                    <span class="text-success d-flex align-items-center justify-content-center">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </span>
                                @else
                                    <span class="text-muted d-flex align-items-center justify-content-center">
                                        <i class="fas fa-circle text-xl"></i>
                                    </span>
                                @endif
                                <span class="ml-3">{{ $feature->title }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($subscription->gateway === 'manual')
                        <a class="btn btn-primary btn-block btn-lg mt-4"
                            href="{{ route('user.packages.pay', [
                                'tableId' => $pricingTableId,
                                'priceId' => $price->id,
                            ]) }}">
                            @lang('Continue')
                        </a>
                    @else
                        <a href="javascript:void(0)"
                            data-href="{{ route('user.subscriptions.change-package.setup', $subscriptionId) }}"
                            class="btn btn-primary btn-block btn-lg mt-4" id="btn-submit-subscription-change">
                            @lang('Continue')
                        </a>
                    @endif

                    <div class="text-muted text-xs text-center mt-2">
                        @lang('By confirming your payment, you allow :app_name to charge your selected payment method for this payment and future payments in accordance with their terms.', [
                            'app_name' => setting('app_name')
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
