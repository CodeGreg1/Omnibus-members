@extends('blogs::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-actions')
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary mt-1">@lang('Create new blog')</a>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('blogs.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="admin-blogs-datatable"></div>
@endsection
