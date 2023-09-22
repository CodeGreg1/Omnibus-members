@extends('subscriptions::layouts.guest')

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="d-flex alitn-items-center justify-content-center mb-3">
                <div class="d-flex flex-column align-items-center">
                    <h4>{{ $title }}</h4>
                    @if ($description)
                        <p class="text-muted">{{ $description }}</p>
                    @endif
                </div>
            </div>

            @if (count($pricingTable) > 1)
                <div class="d-flex align-items-center justify-content-center">
                    <div class="form-group">
                        <div class="selectgroup">
                            @foreach ($pricingTable as $index => $table)
                                <label class="selectgroup-item">
                                    <input type="radio" name="pricing_term" value="{{ $table->term->title }}" class="selectgroup-input" @checked(!$index)>
                                    <span class="selectgroup-button">{{ $table->term->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @forelse ($pricingTable as $index => $table)
                @if (count($table->prices))
                    <div class="row {{ !$index ? '' : 'd-none' }} pricing-terms justify-content-center" id="term-{{ $table->term->title }}">
                        @foreach ($table->prices as $item)
                            @php
                                $price = $item->price;
                                $currentPlan = auth()->check() ? auth()->user()->subscribedToPrice([$price->id], true) : null;
                            @endphp
                            <div class="col-12 col-md-4 col-lg-4">
                                <div class="pricing {{ $item->featured && !$currentPlan ? 'pricing-highlight' : '' }}">
                                    <div class="pricing-title">{{ $price->package->name }}</div>
                                    @if ($price->package->primary_heading)
                                        <div class="text-center mt-3">
                                            {{ $price->package->primary_heading }}
                                        </div>
                                    @endif
                                    @if ($price->package->secondary_heading)
                                        <div class="text-center text-muted text-sm mt-1">
                                            {{ $price->package->secondary_heading }}
                                        </div>
                                    @endif
                                    <div class="pricing-padding">
                                        <div class="pricing-price">
                                            <div>{{ $price->amount_display}}</div>
                                            @if ($price->compare_at_price_display)
                                                <h6 class="text-muted">
                                                    <s>{{ $price->compare_at_price_display}}</s>
                                                </h6>
                                            @endif

                                            <div class="pricing-interval-duration">
                                                @if ($price->type === 'recurring')
                                                    per @if ($table->term->interval_count > 1)
                                                        {{ $table->term->interval_count }}
                                                    @endif {{ \Str::plural($table->term->interval, $table->term->interval_count) }}
                                                @else
                                                    lifetime
                                                @endif
                                            </div>
                                        </div>
                                        <div class="pricing-details">
                                            @foreach ($price->features as $feature)
                                                <div class="pricing-item">
                                                    <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                    <div class="pricing-item-label">{{ $feature->title }}</div>
                                                </div>
                                            @endforeach
                                            @foreach ($features->whereNotIn('id', $price->features->pluck('id')) as $feature)
                                                <div class="pricing-item">
                                                    <div class="pricing-item-icon bg-danger text-white"><i class="fas fa-times"></i></div>

                                                    <div class="pricing-item-label">{{ $feature->title }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="pricing-cta">
                                        @if ($currentPlan)
                                            <a href="javascript:void(0)" disabled="disabled" style="color: var(--success);">
                                                @lang('Current plan')
                                            </a>
                                        @else
                                        <a href="{{ route('user.packages.pay', [
                                                'tableId' => $item->pricing_table_id,
                                                'priceId' => $price->id
                                            ]) }}">
                                            @if ($price->trial_days_count)
                                                Start {{ $price->trial_days_count }} {{ \Str::plural('day', $price->trial_days_count) }} trial
                                            @else
                                                @lang('Subscribe')
                                                <i class="fas fa-arrow-right"></i>
                                            @endif
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info alert-dismissible">
                        <strong>@lang('Notification')</strong>
                        <p>
                            @lang('No active pricing table. Please set your pricing table ') <a href="{{ route('admin.subscriptions.pricing-tables.index') }}"><strong>@lang('here')</strong></a>
                        </p>
                    </div>
                @endif
            @empty
                <div class="alert alert-info alert-dismissible">
                    <strong>@lang('Notification')</strong>
                    <p>
                        @if (auth()->check() && auth()->user()->isAdmin())
                            @lang('No active pricing table. Please set your pricing table ') <a href="{{ route('admin.subscriptions.pricing-tables.index') }}"><strong>@lang('here')</strong></a>
                        @else
                            @lang('No pricing table is being set.')
                        @endif
                    </p>
                </div>
            @endforelse

            <div class="d-flex align-items-center justify-content-center pt-3 pb-5">
                <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">
                    <i class="fas fa-angle-left mr-2"></i>
                    @lang('Back to Dashboard')
                </a>
            </div>

        </div>
    </section>
@endsection
