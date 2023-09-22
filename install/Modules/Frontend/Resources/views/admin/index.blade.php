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
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Frontend')</a>
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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.update') }}" data-type="general">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.index') }}">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="name">@lang('Page title') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text"
                                name="frontend_page_title"
                                class="form-control"
                                value="{{ setting('frontend_page_title') }}"
                                data-max-size="70"
                                required>
                            <div class="mt-1 text-muted form-help float-right">
                                <span class="character_length">{{ \Illuminate\Support\Str::length(setting('frontend_page_title')) }}</span>
                                <span> @lang('of 70 characters used')</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">@lang('Page description') <span class="text-muted">(@lang('Optional'))</span></label>
                            <textarea name="frontend_page_description"
                                class="form-control" data-max-size="320">{{ setting('frontend_page_description') }}</textarea>
                            <div class="mt-1 text-muted form-help float-right">
                                <span class="character_length">{{ \Illuminate\Support\Str::length(setting('frontend_page_description')) }}</span>
                                <span> @lang('of 320 characters used')</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="frontend_social_sharing_image">@lang('Social sharing image') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div data-image-gallery="2100" name="frontend_social_sharing_image">
                                @if (setting('frontend_social_sharing_image'))
                                    <ul class="list-group">
                                        <li class="list-group-item" data-id="0">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="gallery-photo-preview">
                                                        <img class="rounded" src="{{ setting('frontend_social_sharing_image') }}" alt="@lang('Social sharing image')" data-dz-thumbnail="" data-src="{{ setting('frontend_social_sharing_image') }}">
                                                    </div>
                                                </div>
                                                <div class="col overflow-hidden">
                                                    <h6 class="text-sm mb-1 image-name" data-name="@lang('Social sharing image')">@lang('Social sharing image')</h6>
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
                            <small class="form-text text-muted form-help">
                                @lang('Recommended size: 1200 x 628 px')
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <label for="frontend_primary_menu">@lang('Primary Menu') <span class="text-muted">(@lang('Required'))</span></label>
                            <select class="form-control select2" name="frontend_primary_menu" id="">
                                <option value="" selected>@lang('Select menu')</option>
                                @foreach ($primary_menus as $menu)
                                    <option value="{{ $menu->id }}" @selected($menu->id == setting('frontend_primary_menu'))>{{ $menu->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
