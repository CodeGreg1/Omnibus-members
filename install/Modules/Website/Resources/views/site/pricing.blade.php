@extends('website::layouts.site')

@section('page-class', 'unsticky-navbar')

@section('breadcrumbs-position', 'centered')

@section('content')
    <!-- START PRICING SECTION -->
    <section class="pricing-section">
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">@lang('Select our affordable pricing')</p>
            <h2 class="section-heading">@lang('Our pricing')</h2>
        </div>
        <div class="container position-relative z-index-1">
            <ul class="nav nav-pills d-flex align-items-center justify-content-center mb-25" id="pills-tab" role="tablist">
                @foreach ($prices as $index => $priceItemCollection)
                    <li class="nav-item" role="presentation">
                        @if (!$index)
                            <button class="nav-link active" id="pricing-{{ $priceItemCollection->id }}-tab" data-bs-toggle="pill" data-bs-target="#pricing-{{ $priceItemCollection->id }}" type="button" role="tab" aria-controls="pricing-{{ $priceItemCollection->id }}" aria-selected="true">{{ $priceItemCollection->term->title }}</button>
                        @else
                            <button class="nav-link" id="pricing-{{ $priceItemCollection->id }}-tab" data-bs-toggle="pill" data-bs-target="#pricing-{{ $priceItemCollection->id }}" type="button" role="tab" aria-controls="pricing-{{ $priceItemCollection->id }}" aria-selected="false">{{ $priceItemCollection->term->title }}</button>
                        @endif
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="pills-tabContent">
                @foreach ($prices as $index => $priceItemCollection)
                    @if (!$index)
                        <div class="tab-pane fade show active" id="pricing-{{ $priceItemCollection->id }}" role="tabpanel" aria-labelledby="pricing-{{ $priceItemCollection->id }}-tab">
                    @else
                        <div class="tab-pane fade show" id="pricing-{{ $priceItemCollection->id }}" role="tabpanel" aria-labelledby="pricing-{{ $priceItemCollection->id }}-tab">
                    @endif
                        <div class="row justify-content-center">
                            @foreach ($priceItemCollection->prices as $i => $priceItem)
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="single-pricing {{ $priceItem->featured ? 'active' : '' }}">
                                        <h4>{{ $priceItem->price->package->name }}</h4>
                                        <h3>
                                            {{ $priceItem->price->user_amount_display }}
                                            @if ($priceItem->price->type === 'recurring')
                                                <span class="pricing-frequency">/&nbsp;
                                                    @if ($priceItemCollection->term->interval_count > 1)
                                                        {{ $priceItemCollection->term->interval_count . ' ' }}
                                                    @endif
                                                    {{ \Str::plural($priceItemCollection->term->interval, $priceItemCollection->term->interval_count) }}
                                                </span>
                                            @endif
                                        </h3>

                                        <ul>
                                            @foreach ($priceItem->price->features as $feature)
                                                <li class="d-flex align-items-center">
                                                    <div class="pricing-item-icon">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <div class="pricing-item-details">{{ $feature->title }}</div>
                                                </li>
                                            @endforeach

                                            @foreach ($features->whereNotIn('id', $priceItem->price->features->pluck('id')) as $feature)
                                                <li class="d-flex align-items-center">
                                                    <div class="pricing-item-icon bg-danger text-white">
                                                        <i class="fas fa-times"></i>
                                                    </div>
                                                    <div class="pricing-item-details">{{ $feature->title }}</div>
                                                </li>
                                            @endforeach
                                        </ul>

                                        @php
                                            $class = $priceItem->featured ? 'btn btn-primary btn-lg btn-pricing' : 'btn btn-outline-primary btn-lg btn-outline-3px btn-pricing';
                                        @endphp

                                        <a href="{{ route('user.packages.pay', [
                                            'tableId' => $table->id,
                                            'priceId' => $priceItem->price->id
                                        ]) }}" class="{{ $class }}">@lang('Subscribe')</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- END PRICING SECTION -->
@endsection
