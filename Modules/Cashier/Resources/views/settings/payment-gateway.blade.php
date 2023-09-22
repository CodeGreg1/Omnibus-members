@extends('settings::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Settings')</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xm-12">
            @include('settings::admin.partials.menus')
        </div>
        <div class="col-lg-9 col-md-9 col-xm-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="dropdown d-inline">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                                <i class="fas fa-exclamation-circle text-warning mr-1"></i>
                                @lang('Sandbox Mode')
                            @else
                                <i class="fas fa-check-circle text-success mr-1"></i>
                                @lang('Live Mode')
                            @endif
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item has-icon btn-toggle-gateway-mode {{ setting('cashier_mode', 'sandbox') === 'sandbox' ? 'disabled' : '' }}" href="#" role="button" data-value="sandbox" @disabled(setting('cashier_mode', 'sandbox') === 'sandbox')>
                                <i class="fas fa-exclamation-circle text-warning"></i> @lang('Sandbox Mode')
                            </a>
                            <a class="dropdown-item has-icon btn-toggle-gateway-mode {{ setting('cashier_mode', 'sandbox') === 'live' ? 'disabled' : '' }}" href="#" role="button" data-value="live" @disabled(setting('cashier_mode', 'sandbox') === 'live')>
                                <i class="fas fa-check-circle text-success"></i> @lang('Live Mode')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-2 pb-0">
                    <ul class="nav nav-tabs" id="gateway-tablist" role="tablist">
                        <li class="nav-item pl-4">
                            <a class="nav-link active" id="paypal-gateway-tab" data-toggle="tab" href="#paypal-gateway" role="tab" aria-controls="paypal-gateway" aria-selected="true" data-value="paypal">@lang('PayPal')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="stripe-gateway-tab" data-toggle="tab" href="#stripe-gateway" role="tab" aria-controls="stripe-gateway" aria-selected="false" data-value="stripe">@lang('Stripe')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="razorpay-gateway-tab" data-toggle="tab" href="#razorpay-gateway" role="tab" aria-controls="razorpay-gateway" aria-selected="false" data-value="razorpay">@lang('Razorpay')</a>
                        </li>
                        <li class="nav-item pr-4">
                            <a class="nav-link" id="mollie-gateway-tab" data-toggle="tab" href="#mollie-gateway" role="tab" aria-controls="mollie-gateway" aria-selected="false" data-value="mollie">@lang('Mollie')</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body pt-2">
                    <div>
                        <div class="tab-content" id="gateway-tablist-content">
                            <div class="tab-pane fade show active" id="paypal-gateway" role="tabpanel" aria-labelledby="paypal-gateway-tab">
                                @include('cashier::settings.partials.paypal')
                            </div>
                            <div class="tab-pane fade" id="stripe-gateway" role="tabpanel" aria-labelledby="stripe-gateway-tab">
                                @include('cashier::settings.partials.stripe')
                            </div>
                            <div class="tab-pane fade" id="razorpay-gateway" role="tabpanel" aria-labelledby="razorpay-gateway-tab">
                                @include('cashier::settings.partials.razorpay')
                            </div>
                            <div class="tab-pane fade" id="mollie-gateway" role="tabpanel" aria-labelledby="mollie-gateway-tab">
                                @include('cashier::settings.partials.mollie')
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-primary btn-lg float-right" id="btn-save-cashier-gateway-settings" type="button">@lang('Save')</button>
                            <a href="{{ route('admin.settings.subscriptions') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
