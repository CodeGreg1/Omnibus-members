@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="d-flex align-items-center justify-content-between mt-4 mt-lg-0">
        <div class="btn-group ml-lg-2 mr-3">
            <a href="{{ route('admin.subscriptions.pricing-tables.create') }}" class="btn btn-icon icon-left btn-primary">
                <i class="fas fa-plus"></i>
                @lang('New pricing table')
            </a>
        </div>
        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    @include('partials.messages')
    <div id="admin-pricing-tables"></div>
@endsection
