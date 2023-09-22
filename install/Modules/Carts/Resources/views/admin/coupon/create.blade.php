@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/tom-select/dist/css/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-daterangepicker/css/daterangepicker.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-daterangepicker/js/daterangepicker.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.coupons.index') }}">@lang('Coupons')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="mt-4 mt-lg-0">
        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="createCouponForm">
                        <div class="form-group">
                            <label for="name">@lang('Name')</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="@lang('All time discount')">
                            <small class="form-text text-muted form-help">
                                @lang('Coupons can be used to payments, or customer subscriptions').
                            </small>
                        </div>
                        <div class="form-group">
                            <label class="d-block">@lang('Type')</label>
                            <div class="form-check">
                                <input class="form-check-input discountType" type="radio" name="type" id="type-percentage" checked value="percentage">
                                <label class="form-check-label" for="type-percentage">
                                    @lang('Percentage discount')
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input discountType" type="radio" name="type" id="type-fixed" value="fixed">
                                <label class="form-check-label" for="type-fixed">
                                    @lang('Fixed amount discount')
                                </label>
                            </div>
                        </div>
                        <div class="typePanel row" id="percentageDiscountType">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="d-block" for="percentage_value">@lang('Percentage off')</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="percentage_value" name="percentage_value">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="typePanel row d-none" id="fixedDiscountType">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="d-block" for="fixed_amount">@lang('Discount amount')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <select class="custom-select" id="currency" name="currency" style="width:100px;">
                                                @foreach ($currencies as $currencyItem)
                                                    <option
                                                        value="{{ $currencyItem->code }}"
                                                        @selected($currencyItem->code === $currency)
                                                    >{{ $currencyItem->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="text" class="form-control" id="fixed_amount" name="fixed_amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" id="dateLimit" name="withLimitDate" value="1">
                            <label class="form-check-label" for="dateLimit">
                                @lang('Limit the date range when customers can redeem this coupon')
                            </label>
                        </div>
                        <div class="d-none" id="dateLimitPanel">
                            <div class="form-inline mt-2 px-4 mb-2">
                                <div class="form-group">
                                    <label for="limitDate">@lang('Redeemed until')</label>
                                    <input type="text" class="form-control ml-2" id="redeem_until" name="redeem_until">
                                </div>
                            </div>
                        </div>
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" id="redeemLimit" name="withRedeemLimit" value="1">
                            <label class="form-check-label" for="redeemLimit">
                                @lang('Limit the total number of times this coupon can be redeemed')
                            </label>
                        </div>
                        <div class="d-none" id="redeemLimitPanel">
                            <div class="form-group mt-2 px-4 mb-0">
                                <div class="col-12 col-md-6 px-0">
                                    <input type="text" id="redeem_limit_count" class="form-control" aria-describedby="redeem_limit_count" name="redeem_limit_count">
                                </div>
                                <small class="form-text text-muted form-help">
                                    @lang("This limit applies across customers so it won't prevent a single customer from redeeming multiple times").
                                </small>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-whitesmoke">
                    <button type="submit" class="btn btn-primary btn-lg float-right" id="btn-create-coupon">@lang('Create')</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
