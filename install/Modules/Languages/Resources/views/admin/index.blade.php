@extends('languages::layouts.master')

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
    @can('admin.languages.sync-all-language')
        <button 
            class="btn btn-warning mt-1" id="btn-sync-all-language">
            @lang('Sync All')
        </button>
    @endcan

    @can('admin.languages.add-language-phrase')
        <button 
            class="btn btn-info mt-1" 
            data-toggle="modal" 
            data-target="#add-language-phrase-modal"
            data-backdrop="static" 
            data-keyboard="false">
            @lang('Add Language Phrase')
        </button>
    @endcan

    @can('admin.languages.create')
        <a href="{{ route('admin.languages.create') }}" class="btn btn-primary mt-1">
            @lang('Create new language')
        </a>
    @endcan
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

@section('content')
    @can('admin.modules.index')
        <div class="alert alert-info alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>NOTES</strong> 
            <p>1. Each module language is separated. If you need to update or add a new phrase please visit the <a href="{{ route('admin.modules.index') }}"><b>modules manager</b></a>.</p>
            <p>2. Our Google translate button uses FREE API be sure to check the limitations here before using it: <a href="https://github.com/Stichoza/google-translate-php#known-limitations">https://github.com/Stichoza/google-translate-php#known-limitations</a>. We suggest not too fast when clicking the button to translate the phrase to prevent banning of your IP address.</p>
        </div>
    @endcan
    @include('partials.messages')
    <div id="languages-datatable"></div>
@endsection

@section('modals')
    @include('languages::modals.add-language-phrase-modal')
@endsection