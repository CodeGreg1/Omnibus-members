@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-actions')
    <div class="d-flex align-items-center justify-content-between mt-4 mt-lg-0">
        <div class="btn-group ml-lg-2 mr-3">
            @if ($hasActiveGateways)
                <a href="{{ route('admin.subscriptions.packages.create') }}" class="btn btn-primary" @disabled($hasActiveGateways)>@lang('New Package')</a>
            @endif
        </div>

        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Packages')</a>
        </li>
    </ol>
@endsection

@section('content')
    @if (!$hasPricingTable)
        <div class="alert alert-info alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>@lang('Notes')</strong>
            <p>
                @lang('To display packages to pricing page, create pricing table. Goto') <a href="{{ route('admin.subscriptions.pricing-tables.index') }}"><strong>@lang('this link')</strong></a>
            </p>
        </div>
    @endif

    <section class="section">
        <div class="section-body">
            @if (!$hasActiveGateways)
                <div class="alert alert-info">
                    <p>@lang('Please set atleast one payment gateway to create packages.')</p>
                    <a href="{{ route('admin.settings.payment-gateways') }}" class="btn btn-warning mt-2">@lang('Set up gateways')</a>
                </div>
            @endif

            <div id="packages-datatable"></div>
        </div>
    </section>
@endsection
