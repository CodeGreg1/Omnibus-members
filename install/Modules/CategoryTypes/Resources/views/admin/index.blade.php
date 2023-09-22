@extends('categorytypes::layouts.master')

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
    @can('admin.category-types.create')
        <a href="{{ route('admin.category-types.create') }}" class="btn btn-primary mt-1">@lang('Create new category type')</a>
    @endcan
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Category types')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="admin-categorytypes-datatable"></div>
@endsection