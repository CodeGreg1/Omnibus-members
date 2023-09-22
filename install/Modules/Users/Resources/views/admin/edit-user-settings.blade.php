@extends('users::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.users.index') }}"> {!! config('users.name') !!}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{$pageTitle}}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row admin-view-user">
        <div class="col-12 col-sm-12 col-lg-4">
            @include('users::admin.partials.show-profile-widget')
        </div>

        <div class="col-12 col-sm-12 col-lg-8">
            @include('users::admin.partials.show-user-menus')

            @include('users::admin.partials.edit-user-settings-form')
        </div>
    </div>
@endsection

@section('modals')
    @if (Module::has('Subscriptions') && setting('allow_subscriptions') === 'enable' && isset($subscription))
        @include('subscriptions::admin.modals.upgrade-package')
    @endif

    @if (setting('allow_wallet') === 'enable')
        @include('wallet::admin.modals.add-balance')
        @include('wallet::admin.modals.deduct-balance')
    @endif
@endsection
