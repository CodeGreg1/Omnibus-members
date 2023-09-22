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

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.contact-details.update') }}" data-type="contact_details">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.contact-details.index') }}">
                    <div class="card-body pb-0">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="app_address">@lang('Address') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <textarea class="form-control" name="app_address">{{ setting('app_address') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="app_email">@lang('Email') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="email"
                                        name="app_email"
                                        class="form-control"
                                        value="{{ setting('app_email') }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="app_phone">@lang('Phone number') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <input type="text"
                                        name="app_phone"
                                        class="form-control"
                                        value="{{ setting('app_phone') }}">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.contact-details.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
