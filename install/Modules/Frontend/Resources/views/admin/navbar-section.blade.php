@extends('frontend::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.navbar-section.update') }}" data-type="header_section">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.navbar-section.index') }}">
                    <div class="card-body">

                        <div class="row">

                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label>@lang('Background color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_navbar_bg_color', '#1f232f') }}" name="frontend_navbar_bg_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label>@lang('Menu toggler icon color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_navbar_menu_toggler_icon_color', '#222') }}" name="frontend_navbar_menu_toggler_icon_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group mb-0 mt-20">
                                    <label>@lang('Menu text color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_navbar_menu_text_color') }}" name="frontend_navbar_menu_text_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group mb-0 mt-20">
                                    <label>@lang('Menu text hover color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_navbar_menu_text_hover_color') }}" name="frontend_navbar_menu_text_hover_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group mb-0 mt-20">
                                    <label>@lang('Menu text active color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_navbar_menu_text_active_color') }}" name="frontend_navbar_menu_text_active_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.breadcrumb-section.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
