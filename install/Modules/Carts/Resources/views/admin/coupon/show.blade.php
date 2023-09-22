@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/tom-select/dist/css/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="{{ route('admin.coupons.index') }}"> @lang('Coupons')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="{{ route('admin.coupons.show', [$coupon]) }}"> {{ $coupon->name }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="d-flex align-items-center justify-content-between mt-4 mt-lg-0">
        <div class="btn-group ml-lg-2 mr-3">
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-icon icon-left btn-success">
                <i class="fas fa-pencil-alt"></i>
                @lang('Edit Coupon')
            </a>
        </div>

        @include('cashier::partials.live-mode-status')
    </div>

@endsection

@section('content')
    <section class="section">
        <input type="hidden" id="Coupon_id" value="{{ $coupon->id }}">
        <input type="hidden" id="Packages" value="{{ implode(",", $packages->pluck('id')->toArray()) }}">
        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="general-settings-tab" data-toggle="pill" data-target="#general-settings" type="button" role="tab" aria-controls="general-settings" aria-selected="true">@lang('General settings')</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="coupon-redemptions-user-tab" data-toggle="pill" data-target="#coupon-redemptions-user" type="button" role="tab" aria-controls="coupon-redemptions-user" aria-selected="false">@lang('Subscriptions')</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">
                            <div id="promo-codes-datatable"></div>
                        </div>
                        <div class="tab-pane fade" id="coupon-redemptions-user" role="tabpanel" aria-labelledby="coupon-redemptions-user-tab">
                            <div id="coupon-subscriptions-datatable"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6>@lang('Coupon details')</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Name')</label>
                                    <p class="mb-0">{{ $coupon->name }}</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Terms')</label>
                                    <p class="mb-0">
                                        @if ($coupon->amount_type === 'fixed')
                                            $
                                        @endif
                                        {{ $coupon->amount }}
                                        @if ($coupon->amount_type === 'percentage')
                                             %
                                        @endif
                                        @if ($coupon->billing_duration === 0)
                                             @lang('Forever')
                                        @elseif ($coupon->billing_duration === 1)
                                             @lang('Once')
                                        @else
                                             {{ $coupon->billing_duration }} @lang('billing periods')
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Duration')</label>
                                    @if ($coupon->billing_duration === 0)
                                        <p class="mb-0">@lang('Forever')</p>
                                    @elseif ($coupon->billing_duration === 1)
                                        <p class="mb-0 text-secondary">@lang('Once')</p>
                                    @else
                                        <p class="mb-0 text-secondary">{{ $coupon->billing_duration }} @lang('billing periods')</p>
                                    @endif
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Usage')</label>
                                    <p class="mb-0">{{ $timesRedeemed ? $timesRedeemed . ' ' . __('redemptions') : __('No redemptions yet') }}</p>
                                </div>
                                <div class="form-group col-12 mb-0">
                                    <label class="mb-0">Limit</label>
                                    <p class="mb-0">{{ $coupon->redeem_limit_count ? $coupon->redeem_limit_count . ' ' . __('redemptions') : __('No limit') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modals')
    @include('carts::admin.modals.create-promo-code')
@endsection
