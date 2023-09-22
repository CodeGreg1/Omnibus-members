@extends('availablecurrencies::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@section('module-actions')
    @can('admin.available-currencies.create')
        <a href="{{ route('admin.available-currencies.create') }}" class="btn btn-primary mt-1">@lang('Create new currency')</a>
    @endcan
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Currencies')</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="alert alert-info">
        <p><i class="fas fa-info-circle"></i> @lang('System Currency exchange rate should always 1.00. Please update the other currencies exchange rate according with the current system currency.')</p>
    </div>
    @include('partials.messages')
    <div id="admin-availablecurrencies-datatable"></div>
@endsection