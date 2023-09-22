@extends('subscriptions::layouts.master')

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
        <li class="breadcrumb-item active">
            <a href="#"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')

    @if ($isSubscribed)
        @if (count($modulePermissions))
            <div class="card">
                <div class="card-header">
                    <h4>@lang('Module usages')</h4>
                </div>
                <div class="card-body py-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Module')</th>
                                <th>@lang('Usage')</th>
                                <th class="text-right">@lang('Expires')</th>
                            </tr>
                        </thead>
                        @foreach ($modulePermissions as $modulePermission)
                            <tr>
                                <td colspan="3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <strong>{{ $modulePermission['title'] }}</strong>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($modulePermission['permissions'] as $permission)
                                <tr>
                                    <td class="pl-4" style="vertical-align: middle;">
                                        {{ str_replace("Index", "Manage", $permission->display_name) }}
                                    </td>
                                    <td>
                                        @if ($permission->limit)
                                            {{ $permission->used }} / {{ $permission->limit }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {{ $permission->ends }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-light">
                <div class="alert-title">@lang('Notification')</div>
                @lang("You don't have any module subscribed to.")
            </div>
        @endif
    @else
        <div class="alert alert-light">
            <div class="alert-title">@lang('Notification')</div>
            @lang("You don't subscribed to this module.")
        </div>
    @endif
@endsection
