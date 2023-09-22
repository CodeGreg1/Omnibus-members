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
                <span>@lang('KoolAdmin Version')</span>
                <span>{{env('KOOL_ADMIN_VERSION', '1.0')}}</span>
            </h6>
        </li>
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('Laravel Version')</span>
                <span>{{ Illuminate\Foundation\Application::VERSION }}</span>
            </h6>
        </li>
        <li class="list-group-item py-3">
            <h6 class="d-flex justify-content-between mb-0">
                <span>@lang('Timezone')</span>
                <span>{{ setting('timezone_key_value') }}</span>
            </h6>
        </li>
    </ul>
@endsection
