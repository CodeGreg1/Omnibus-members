@extends('frontend::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.footer-section.update') }}" data-type="footer_section">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.footer-section.index') }}">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Background color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_footer_bg_color', '#1f232f') }}" name="frontend_footer_bg_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>@lang('Heading color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_footer_heading_color', '#fff') }}" name="frontend_footer_heading_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>@lang('Text color') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" value="{{ setting('frontend_footer_text_color', '#ffffffbf') }}" name="frontend_footer_text_color">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="frontend_footer_menu1_title">@lang('Menu 1 title') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <input type="text"
                                        name="frontend_footer_menu1_title"
                                        class="form-control"
                                        value="{{ setting('frontend_footer_menu1_title') }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="frontend_footer_menu1">@lang('Menu 1') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <select class="form-control select2" name="frontend_footer_menu1" id="">
                                        <option value="" selected>@lang('Select menu')</option>
                                        @foreach ($frontend_menus as $menu)
                                            <option value="{{ $menu->id }}" @selected($menu->id == setting('frontend_footer_menu1'))>{{ $menu->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="frontend_footer_menu2_title">@lang('Menu 2 title') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <input type="text"
                                        name="frontend_footer_menu2_title"
                                        class="form-control"
                                        value="{{ setting('frontend_footer_menu2_title') }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="frontend_footer_menu2">@lang('Menu 2') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <select class="form-control select2" name="frontend_footer_menu2" id="">
                                        <option value="" selected>@lang('Select menu')</option>
                                        @foreach ($frontend_menus as $menu)
                                            <option value="{{ $menu->id }}" @selected($menu->id == setting('frontend_footer_menu2'))>{{ $menu->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="frontend_footer_about_us">@lang('About us') <span class="text-muted">(@lang('Required'))</span></label>
                                    <textarea name="frontend_footer_about_us"
                                        class="form-control">{{ setting('frontend_footer_about_us') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="frontend_footer_copyright_text">@lang('Copyright text') <span class="text-muted">(@lang('Required'))</span></label>
                                    <textarea name="frontend_footer_copyright_text"
                                        class="form-control">{{ setting('frontend_footer_copyright_text') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.footer-section.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
