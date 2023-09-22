@extends('databasebackup::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-actions')
    <button type="button" class="btn btn-primary" id="btn-admin-backup-database">@lang('Backup now')</button>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('databasebackup.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')

    <div class="row flex-md-row-reverse">
        <div class="col-lg-4 col-md-4 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>@lang('Settings')</h4>
                </div>

                <form id="admin-database-backup-settings-update-form" method="post">
                    <div class="card-body pb-0">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" name="database_auto_backup" {{ setting('database_auto_backup', 0) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customCheck1">@lang('Automatically backup database using Cron Job')</label>
                            </div>
                        </div>

                        <div class="database-backup-frequency form-group mt-n3 {{ setting('database_auto_backup', 0) ? '' : 'd-none' }}">
                            <select class="form-control" name="database_backup_frequency">
                                <option value="daily" @selected(setting('database_backup_frequency', 'daily') === 'daily')>@lang('Every day')</option>
                                <option value="weekly" @selected(setting('database_backup_frequency', 'daily') === 'weekly')>@lang('Every week')</option>
                                <option value="monthly" @selected(setting('database_backup_frequency', 'daily') === 'monthly')>@lang('Every month')</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.database-backup.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-xm-12">
            <div id="admin-database-backup-datatable"></div>
        </div>
    </div>
@endsection
