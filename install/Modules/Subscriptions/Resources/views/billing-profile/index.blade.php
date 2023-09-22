@extends('profile::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/sticky-sidebar/js/jquery.sticky-sidebar.modified.js') }}"></script>
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-styles')
    {!! theme_style('assets/plugins/bootstrap-social/bootstrap-social.css') !!}
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{$pageTitle}}</a>
        </li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="Subscription_id" value="{{ $subscription ? $subscription->id : '' }}">
    <div class="row">
        <div class="col-md-8">

            @include('subscriptions::billing-profile.partials.subscriptions')
            @include('subscriptions::billing-profile.partials.payments')
        </div>

        <div class="col-md-4 d-none d-sm-block wrapper-main">
            @include('subscriptions::billing-profile.partials.sidebar-sections')
        </div>
    </div>

@endsection

@section('modals')
    @if (session()->has('pruchase'))
        @include('subscriptions::modals.checkout-success')
    @endif
@endsection
