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
            <a href="javascript:void(0)"> @lang('Frontend')</a>
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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.login-register.update') }}" data-type="login_register">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.login-register.index') }}">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-6 col-md-6">

                                <div class="form-group mb-0">
                                    <div>
                                        <label for="show_frontend_privacy_policy_link">@lang('Show Privacy Policy Page Link') <span class="text-muted">(@lang('Required'))</span></label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="show_frontend_privacy_policy_link1" name="show_frontend_privacy_policy_link" class="custom-control-input" value="1" {{ setting('show_frontend_privacy_policy_link') == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_frontend_privacy_policy_link1">@lang('Enable')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="show_frontend_privacy_policy_link2" name="show_frontend_privacy_policy_link" class="custom-control-input" value="0" {{ setting('show_frontend_privacy_policy_link') == 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_frontend_privacy_policy_link2">@lang('Disable')</label>
                                    </div>
                                    <small class='form-text text-muted form-help'>@lang('Display the "Privacy Policy Page" link in the login and register form.')</small>
                                </div>

                            </div>

                            <div class="col-6 col-md-6">

                                <div class="form-group mb-0">
                                    <div>
                                        <label for="show_frontend_terms_and_condition_link">@lang('Show Terms & Conditions link') <span class="text-muted">(@lang('Required'))</span></label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="show_frontend_terms_and_condition_link1" name="show_frontend_terms_and_condition_link" class="custom-control-input" value="1" {{ setting('show_frontend_terms_and_condition_link') == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_frontend_terms_and_condition_link1">@lang('Enable')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="show_frontend_terms_and_condition_link2" name="show_frontend_terms_and_condition_link" class="custom-control-input" value="0" {{ setting('show_frontend_terms_and_condition_link') == 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="show_frontend_terms_and_condition_link2">@lang('Disable')</label>
                                    </div>
                                    <small class='form-text text-muted form-help'>@lang('Display the "Terms & Conditions Page" link in the login and register form.')</small>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.login-register.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
