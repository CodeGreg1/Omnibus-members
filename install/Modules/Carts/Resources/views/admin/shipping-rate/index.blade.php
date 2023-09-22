@extends('carts::layouts.master')

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
            <a href="javascript:void(0)"> {!! $pageTitle !!}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="d-flex align-items-center justify-content-between mt-4 mt-lg-0">
        <div class="btn-group ml-lg-2 mr-3">
            <button
                type="button"
                class="btn btn-icon icon-left btn-primary"
                data-toggle="modal"
                data-target="#create-shipping-rate-modal"
            >
                <i class="fas fa-plus"></i>
                @lang('Shipping rate')
            </button>
        </div>

        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    @include('partials.messages')
    <div id="admin-shipping-rates-datatable"></div>
@endsection

@section('modals')
    @include('carts::admin.modals.create-shipping-rate')
    @include('carts::admin.modals.edit-shipping-rate')
@endsection
