@extends('profile::layouts.master')

@section('module-styles')
    {!! theme_style('assets/plugins/bootstrap-social/bootstrap-social.css') !!}
    <link rel="stylesheet" href="{{ url('plugins/croppie/css/croppie.min.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/sticky-sidebar/js/jquery.sticky-sidebar.modified.js') }}"></script>
    <script src="{{ url('plugins/croppie/js/croppie.min.js') }}"></script>
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

            @include('profile::partials.general-sections.details')

            @include('profile::partials.general-sections.login-service')

            @include('profile::partials.general-sections.address')

            @include('profile::partials.general-sections.company')

            @include('profile::partials.general-sections.timezone')

            @include('profile::partials.general-sections.language')

            @include('profile::partials.general-sections.currency')

        </div>

        <div class="col-md-4 d-none d-sm-block wrapper-main">
            @include('profile::partials.general-sections.sidebar-sections')
        </div>
    </div>

@endsection

@section('modals')
    @include('profile::partials.modals.upload-photo')
@endsection