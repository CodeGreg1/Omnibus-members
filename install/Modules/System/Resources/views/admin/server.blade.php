@extends('system::layouts.master')

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! $pageTitle !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    <ul class="list-group">
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('PHP Version')</span>
                <span>{{ PHP_VERSION }}</span>
            </h6>
        </li>
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('Server Software')</span>
                <span>{{ setting('app_name') }}</span>
            </h6>
        </li>
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('Server IP Address')</span>
                <span>{{ $ip }}</span>
            </h6>
        </li>
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('Server Protocol')</span>
                <span>{{ $scheme }}</span>
            </h6>
        </li>
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('HTTP Host')</span>
                <span>{{ $host }}</span>
            </h6>
        </li>
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('Server Port')</span>
                <span>{{ $port }}</span>
            </h6>
        </li>
    </ul>
@endsection
