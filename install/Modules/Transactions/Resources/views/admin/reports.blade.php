@extends('transactions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-daterangepicker/css/daterangepicker.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-daterangepicker/js/daterangepicker.js') }}"></script>
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
    @include('partials.messages')

    <div class="d-flex align-items-center justify-content-between">
        <a href="javascript:void(0);" class="btn btn-light btn-admin-transactions-report-daterangepicker icon-left btn-icon">
            <i class="fas fa-calendar"></i>
            <span>@lang('Choose date')</span>
        </a>

        <div class="d-flex align-items-center">
            <button class="btn btn-light icon-left btn-icon mr-2 btn-print-transactions" type="button">
                <i class="fas fa-print"></i>
                @lang('Print')
            </button>
            <div>
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download mr-1"></i>
                    @lang('Export')
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item fw-bold btn-export-transaction-excel" href="javascript:void(0)">@lang('Excel')</a>
                    <a class="dropdown-item fw-bold btn-export-transaction-pdf" href="javascript:void(0)">@lang('PDF')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <div id="admin-transactions-report-datatable"></div>
    </div>
@endsection
