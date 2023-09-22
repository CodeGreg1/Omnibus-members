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
                    <strong class="text-white">{{ $price->package->name }}</strong>

                    <div class="d-flex align-items-center mt-2">
                        <strong class="text-white text-4xl checkout-total">
                            {{ $price->getUnitPrice() }}
                        </strong>
                        <span class="ml-3 text-gray-500">
                            @if ($price->isRecurring())
                                {{ $price->term->title }}
                            @else
                                @lang('Lifetime')
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="checkout-payment">
                <div class="d-flex flex-column mt-3 mt-lg-0">
                    <span class="text-muted mb-3">@lang('Features')</span>

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

                    <a href="{{ $return_url }}" class="btn btn-primary btn-block btn-lg mt-4">
                        @lang('Continue')
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
