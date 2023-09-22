@extends('languages::layouts.master')

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
            <a href="{{ route('admin.languages.index') }}"> @lang('Languages')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Update :title Language', ['title' => $languages->title])</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="languages-edit-datatable" data-id="{{ $languages->id }}" data-code="{{ $languages->code }}"></div>
@endsection