@extends('subscriptions::layouts.master')

@section('module-actions')
    <div class="btn-group ml-lg-2 mt-2">
        <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#create-feature-modal">@lang('New feature')</button>
    </div>
@endsection

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="{{ route('admin.subscriptions.packages.index') }}"> @lang('Packages')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="admin-package-features-datatable"></div>
@endsection

@section('modals')
    @include('subscriptions::admin.modals.create-feature')
    @include('subscriptions::admin.modals.edit-feature')
@endsection
