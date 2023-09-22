@extends('maintenance::layouts.master')

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
    <div class="card card-form">
        <form id="maintenance-settings-form" method="post">
            <div class="card-body pb-0">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="maintenance_enabled" {{ $enabled ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customCheck1">@lang('Enable maintenance mode')</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>@lang('Secret code') <span class="text-muted">(@lang('Required'))</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">{{ url('/') }}/</span>
                        </div>
                        <input type="text" class="form-control" name="maintenance_secret_code" value="{{ setting('maintenance_secret_code') }}">
                    </div>
                    <small class="form-text text-muted form-help">
                        @lang('Accepts only letters, numbers, dash and underscore.')
                    </small>
                </div>

                <div class="form-group">
                    <label>@lang('Maintenance Reason') <span class="text-muted">(@lang('Optional'))</span></label>
                    <textarea class="form-control" name="maintenance_reason">{{ setting('maintenance_reason') }}</textarea>
                </div>
            </div>

            <div class="card-footer bg-whitesmoke">
                <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                <a href="{{ route('admin.maintenance.settings.update') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
            </div>
        </form>
    </div>
@endsection
