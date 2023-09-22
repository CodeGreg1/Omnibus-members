@extends('frontend::layouts.master')

@section('module-styles')
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.frontends.settings.index') }}"> @lang('Frontend')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xm-12">
            @include('frontend::admin.partials.menus')
        </div>
        <div class="col-lg-9 col-md-9 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.cookie.update') }}" data-type="cookie">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.cookie.index') }}">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="frontend_cookie_duration">@lang('Cookie duration') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="frontend_cookie_duration" value="{{ setting('frontend_cookie_duration') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                @lang('days')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="frontend_cookie_content">@lang('Cookie content') <span class="text-muted">(@lang('Required'))</span></label>
                                    <textarea name="frontend_cookie_content"
                                        class="form-control">{{ setting('frontend_cookie_content') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">

                                <div class="form-group">
                                    <div>
                                        <label for="show_frontend_cookie_privacy_policy_link">@lang('Show Privacy Policy Page Link')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="show_frontend_cookie_privacy_policy_link1" name="show_frontend_cookie_privacy_policy_link" class="custom-control-input" value="1" {{ setting('show_frontend_cookie_privacy_policy_link') == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_frontend_cookie_privacy_policy_link1">@lang('Enable')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="show_frontend_cookie_privacy_policy_link2" name="show_frontend_cookie_privacy_policy_link" class="custom-control-input" value="0" {{ setting('show_frontend_cookie_privacy_policy_link') == 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_frontend_cookie_privacy_policy_link2">@lang('Disable')</label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="frontend_cookie_status">@lang('Status') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select class="form-control select2" name="frontend_cookie_status" id="">
                                        <option value="disabled" @selected('disabled' == setting('frontend_cookie_status'))>@lang('Disable')</option>
                                        <option value="enabled" @selected('enabled' == setting('frontend_cookie_status'))>@lang('Enable')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.cookie.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
