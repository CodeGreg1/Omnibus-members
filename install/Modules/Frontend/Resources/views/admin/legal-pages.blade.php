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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.legal-pages.update') }}" data-type="contact_details">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.legal-pages.index') }}">
                    <div class="card-body pb-0">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="frontend_page_terms">@lang('Page for Terms and Conditions') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select class="form-control select2" name="frontend_page_terms" id="">
                                        <option value="" selected>@lang('Select page')</option>
                                        @foreach ($pages as $page)
                                            <option value="{{ $page->slug }}" @selected($page->slug == setting('frontend_page_terms'))>{{ $page->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="frontend_page_policy">@lang('Page for Privacy Policy') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select class="form-control select2" name="frontend_page_policy" id="">
                                        <option value="" selected>@lang('Select page')</option>
                                        @foreach ($pages as $page)
                                            <option value="{{ $page->slug }}" @selected($page->slug == setting('frontend_page_policy'))>{{ $page->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.legal-pages.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
