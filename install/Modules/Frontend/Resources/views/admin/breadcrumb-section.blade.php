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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.breadcrumb-section.update') }}" data-type="header_section">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.breadcrumb-section.index') }}">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Background style')</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="frontend_breadcrumb_background_style" value="color" class="selectgroup-input" {{ $background_style === 'color' ? 'checked=""' : ''}}>
                                            <span class="selectgroup-button">@lang('Color')</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="frontend_breadcrumb_background_style" value="image" class="selectgroup-input" {{ $background_style === 'image' ? 'checked=""' : ''}}>
                                            <span class="selectgroup-button">@lang('Image')</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 frontend-breadcrumb-bg-color {{ $background_style === 'color' ? '' : 'd-none' }}">
                                <div class="form-group mb-0">
                                    <label>@lang('Background color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_breadcrumb_bg_color') }}" name="frontend_breadcrumb_bg_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 frontend-breadcrumb-bg-image {{ $background_style === 'image' ? '' : 'd-none' }}">
                                <div class="form-group mb-0">
                                    <label for="frontend_breadcrumb_bg_image">@lang('Background image') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div data-image-gallery="frontend-breadcrumb-bg-image" name="frontend_breadcrumb_bg_image">
                                        @if (setting('frontend_breadcrumb_bg_image'))
                                            <ul class="list-group">
                                                <li class="list-group-item" data-id="0">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="gallery-photo-preview">
                                                                <img class="rounded" src="{{ setting('frontend_breadcrumb_bg_image') }}" alt="Logo" data-dz-thumbnail="Logo" data-src="{{ setting('frontend_breadcrumb_bg_image') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col overflow-hidden">
                                                            <h6 class="text-sm mb-1 image-name" data-name="Logo">@lang('Image')</h6>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href="javascript:void(0)" class="dropdown-item btn-remove-selected-gallery-image"><i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-0 mt-20">
                                    <label>@lang('Breadcrumb text color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_breadcrumb_text_color') }}" name="frontend_breadcrumb_text_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-0 mt-20">
                                    <label>@lang('Breadcrumb text hover color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_breadcrumb_text_hover_color') }}" name="frontend_breadcrumb_text_hover_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-0 mt-20">
                                    <label>@lang('Page title color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_breadcrumb_page_title_color') }}" name="frontend_breadcrumb_page_title_color">
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
