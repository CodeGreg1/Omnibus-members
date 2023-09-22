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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.theme-colors.update') }}" data-type="theme_colors">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.theme-colors.index') }}">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="form-label">@lang('Color scheme') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="frontend_color_scheme" value="light" class="selectgroup-input" {{ $colorScheme === 'light' ? 'checked=""' : ''}}>
                                            <span class="selectgroup-button">@lang('Light')</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="frontend_color_scheme" value="dark" class="selectgroup-input" {{ $colorScheme === 'dark' ? 'checked=""' : ''}}>
                                            <span class="selectgroup-button">@lang('Dark')</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 dark-color-wrapper {{ $colorScheme === 'light' ? 'display-none' : '' }}">
                                <div class="form-group mt-10">
                                    <label>@lang('Dark color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_dark_color_scheme', '#30343b') }}" name="frontend_dark_color_scheme">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-4">
                                <div class="form-group mb-0 mt-20">
                                    <label>@lang('Primary color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_primary_color', '#0084ff') }}" name="frontend_primary_color">
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
                                    <label>@lang('Secondary color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_secondary_color', '#6c757d') }}" name="frontend_secondary_color">
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
                                    <label>@lang('Info color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_info_color', '#0dcaf0') }}" name="frontend_info_color">
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
                                    <label>@lang('Success color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_success_color', '#10b981') }}" name="frontend_success_color">
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
                                    <label>@lang('Warning color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_warning_color', '#ffc107') }}" name="frontend_warning_color">
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
                                    <label>@lang('Danger color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_danger_color', '#f65660') }}" name="frontend_danger_color">
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
