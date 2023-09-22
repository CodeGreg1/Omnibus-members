@extends('sitemap::layouts.master')

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('sitemap.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>@lang('Sitemap settings')</h4>
                </div>

                <form id="admin-sitemap-settings-update-form" method="post">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" name="sitemap_auto_rebuild" {{ setting('sitemap_auto_rebuild', 0) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customCheck1">@lang('Automatically rebuild sitemap using Cron Job')</label>
                            </div>
                        </div>

                        <div class="sitemap-rebuild-frequency form-group mt-n3 {{ setting('sitemap_auto_rebuild', 0) ? '' : 'd-none' }}">
                            <select class="form-control" name="sitemap_rebuild_frequency">
                                <option value="daily" @selected(setting('sitemap_rebuild_frequency', 'daily') === 'daily')>@lang('Every day')</option>
                                <option value="weekly" @selected(setting('sitemap_rebuild_frequency', 'daily') === 'weekly')>@lang('Every week')</option>
                                <option value="monthly" @selected(setting('sitemap_rebuild_frequency', 'daily') === 'monthly')>@lang('Every month')</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('Default Change Frequency')</label>
                            <select class="form-control" name="sitemap_change_frequency">
                                <option value="always" @selected(setting('sitemap_change_frequency', 'daily') === 'always')>@lang('Always')</option>
                                <option value="hourly" @selected(setting('sitemap_change_frequency', 'daily') === 'hourly')>@lang('Hourly')</option>
                                <option value="daily" @selected(setting('sitemap_change_frequency', 'daily') === 'daily')>@lang('Daily')</option>
                                <option value="weekly" @selected(setting('sitemap_change_frequency', 'daily') === 'weekly')>@lang('Weekly')</option>
                                <option value="monthly" @selected(setting('sitemap_change_frequency', 'daily') === 'monthly')>@lang('Monthly')</option>
                                <option value="yearly" @selected(setting('sitemap_change_frequency', 'daily') === 'yearly')>@lang('Yearly')</option>
                                <option value="never" @selected(setting('sitemap_change_frequency', 'daily') === 'never')>@lang('Never')</option>
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label>@lang('Default Priority Level')</label>
                            <input type="number" step="0.1" class="form-control" name="sitemap_priority_level" value="{{ setting('sitemap_priority_level', 0.1) }}">
                        </div>
                    </div>

                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.sitemap.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xm-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="" class="mb-0">@lang('Sitemap URL')</label>
                        <small class="form-text text-muted">
                            @lang('Use the link to submit sitemap into search engines')
                        </small>
                        <strong class="mt-2">{{ url('/sitemap.xml') }}</strong>
                    </div>

                    <div class="form-group">
                        <label for="" class="d-block">@lang('Sitemap File')</label>
                        <a href="/sitemap.xml" target="_blank" class="btn btn-success">
                            @lang('View sitemap file')
                        </a>
                    </div>

                    <div class="form-group mb-0">
                        <label for="" class="d-block">@lang('Build your sitemap')</label>
                        <button type="button" class="btn btn-primary" id="admin-sitemap-re-build">
                            @lang('Rebuild sitemap')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
