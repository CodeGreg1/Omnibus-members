@extends('roles::layouts.master')

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
    @can('permissions.index')
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-warning mt-1">@lang('Permissions')</a>
    @endcan

    @can('admin.roles.create')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mt-1">@lang('Create new role')</a>
    @endcan
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Roles')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="roles-datatable"></div>
@endsection
