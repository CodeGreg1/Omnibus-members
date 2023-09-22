@extends('profile::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/sticky-sidebar/js/jquery.sticky-sidebar.modified.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-styles')
    {!! theme_style('assets/plugins/bootstrap-social/bootstrap-social.css') !!}
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{$pageTitle}}</a>
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">

            @include('profile::partials.security-sections.password')
            @include('profile::partials.security-sections.2fa')
            @include('profile::partials.security-sections.devices')

        </div>

        <div class="col-md-4 d-none d-sm-block wrapper-main">
            @include('profile::partials.security-sections.sidebar-sections')
        </div>
    </div>

@endsection
