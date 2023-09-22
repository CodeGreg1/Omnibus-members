@extends('subscriptions::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.subscriptions.packages.show', [$package]) }}"> {{ $package->name }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="{{ route('admin.subscriptions.packages.prices.show', [$package, $price]) }}"> @lang('Price of') {{ $package->name }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="mt-4 mt-lg-0">
        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6>@lang('Details')</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Package')</label>
                                    <p class="mb-0">{{ $package->name }}</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Trial period days')</label>
                                    <p class="mb-0">{{ $price->trial_days_count }}</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Subscriptions')</label>
                                    <p class="mb-0">{{ $price->activeSubscriptionsCount() }} @lang('active')</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Usage type')</label>
                                    <p class="mb-0">{{ $price->type === 'recurring' ? __('Recurring usage') : __('Lifetime usage') }}</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Currency')</label>
                                    <p class="mb-0">{{ $currency->name }}</p>
                                </div>
                                @if ($price->isRecurring())
                                    <div class="form-group col-12">
                                        <label class="mb-0">@lang('Interval')</label>
                                        <p class="mb-0">{{ $price->term->title }}</p>
                                    </div>
                                @endif
                                <div class="form-group col-12 mb-0">
                                    <label class="mb-0">@lang('Price per unit')</label>
                                    <p class="mb-0">{{ $price->amount_display }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
