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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.custom-css-js.update') }}" data-type="custom_css_js">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.custom-css-js.index') }}">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="frontend_custom_css">@lang('CSS code') <span class="text-muted">(@lang('Optional'))</span></label>
                            <textarea name="frontend_custom_css"
                                class="form-control">{{ setting('frontend_custom_css') }}</textarea>
                            <small class="form-text text-muted form-help">
                                @lang('Write Your CSS Code without style tag!')
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="frontend_custom_js">@lang('JS code') <span class="text-muted">(@lang('Optional'))</span></label>
                            <textarea name="frontend_custom_js"
                                class="form-control">{{ setting('frontend_custom_js') }}</textarea>
                            <small class="form-text text-muted form-help">
                                @lang('Write Your JS Code without script tag!')
                            </small>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.custom-css-js.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
