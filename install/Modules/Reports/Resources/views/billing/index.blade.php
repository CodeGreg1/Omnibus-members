@extends('reports::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-daterangepicker/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/chart.js/js/chart.umd.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-daterangepicker/js/daterangepicker.js') }}"></script>
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang($pageTitle)</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="d-flex align-items-center">
        <a href="javascript:;" class="btn btn-light daterange-btn icon-left btn-icon">
            <i class="fas fa-calendar"></i>
            <span>@lang('Choose date')</span>
        </a>
    </div>
    <div class="row px-2 mt-3 billing-report-card">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Subscription revenue')</span>
                    </div>
                    <div class="card-body pb-3 signup_revenue">
                        $0.00
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Renewal revenue')</span>
                    </div>
                    <div class="card-body pb-3 renewal_revenue">
                        $0.00
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Renewed subscriptions')</span>
                    </div>
                    <div class="card-body pb-3 subscription_renewal">
                        0
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Total subscriptions')</span>
                    </div>
                    <div class="card-body pb-3 total_subscriptions">
                        0
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Cancelled subscriptions')</span>
                    </div>
                    <div class="card-body pb-3 subscription_cancellations">
                        0
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Ended subscriptions')</span>
                    </div>
                    <div class="card-body pb-3 subscriptions_ended">
                        0
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Active subscriptions')</span>
                    </div>
                    <div class="card-body pb-3 current_subscriptions">
                        0
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 px-2">
            <div class="card card-statistic-1 mb-3">
                <div class="card-wrap">
                    <div class="card-header" style="line-height: 12px;">
                        <span class="text-sm">@lang('Trialing Subscriptions')</span>
                    </div>
                    <div class="card-body pb-3 trialing_subscriptions">
                        0
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <div id="admin-billing-reports-datatable"></div>
    </div>
@endsection
