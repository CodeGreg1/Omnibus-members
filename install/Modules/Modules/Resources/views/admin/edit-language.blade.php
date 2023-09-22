@extends('modules::layouts.master')

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
        <li class="breadcrumb-item">
            <a href="{{ route('admin.modules.index') }}"> @lang('Modules')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.modules.show-language', $module->id) }}"> @lang('Module Languages')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Update Language')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="module-edit-language-datatable" data-id="{{ $module->id }}" data-code="{{ $code }}"></div>
@endsection