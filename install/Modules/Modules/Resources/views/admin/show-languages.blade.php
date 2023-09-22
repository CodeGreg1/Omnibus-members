@extends('modules::layouts.master')

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
    @can('admin.modules.add-language-phrase')
        <button 
            class="btn btn-primary" 
            data-toggle="modal" 
            data-target="#add-language-phrase-modal"
            data-backdrop="static" 
            data-keyboard="false">
            @lang('Add Language Phrase')
        </button>
    @endcan
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.modules.index') }}"> @lang('Modules')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $module->name }} @lang('Module Languages')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="module-language-datatable" data-id="{{ $module->id }}"></div>
@endsection

@section('modals')
    @include('languages::modals.add-language-phrase-modal')
@endsection