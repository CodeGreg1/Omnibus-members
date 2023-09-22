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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.logos.update') }}" data-type="logos">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.logos.index') }}">
                    <div class="card-body">

                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="frontend_dark_logo">@lang('Dark logo') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div data-image-gallery="2301" name="frontend_dark_logo">
                                        @if (setting('frontend_dark_logo'))
                                            <ul class="list-group">
                                                <li class="list-group-item" data-id="0">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="gallery-photo-preview">
                                                                <img class="rounded" src="{{ setting('frontend_dark_logo') }}" alt="@lang('Dark logo')" data-dz-thumbnail="@lang('Dark logo')" data-src="{{ setting('frontend_dark_logo') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col overflow-hidden">
                                                            <h6 class="text-sm mb-1 image-name" data-name="@lang('Dark logo')">@lang('Dark logo')</h6>
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

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="frontend_white_logo">@lang('White logo') <span class="text-muted">(@lang('Required'))</span></label>
                                    <div data-image-gallery="2302" name="frontend_white_logo">
                                        @if (setting('frontend_white_logo'))
                                            <ul class="list-group">
                                                <li class="list-group-item" data-id="0">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="gallery-photo-preview">
                                                                <img class="rounded" src="{{ setting('frontend_white_logo') }}" alt="@lang('White logo')" data-dz-thumbnail="@lang('White logo')" data-src="{{ setting('frontend_white_logo') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col overflow-hidden">
                                                            <h6 class="text-sm mb-1 image-name" data-name="@lang('White logo')">@lang('White logo')</h6>
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
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.logos.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
