@extends('pages::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ url('plugins/tinymce/js/tinymce.min.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.pages.index') }}"> @lang('Pages')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Create page')</a>
        </li>
    </ol>
@endsection

@section('content')
    <form class="admin-page-form" id="admin-page-create-form" method="post">
        <div class="row">
            <div class="col-12">

                <div class="card card-form">
                    <div class="card-header">
                        <h4>{{ $pageTitle }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">


                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span
                                            class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description') <span
                                            class="text-muted">(@lang('Optional'))</span></label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Type') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="type" class="form-control select2" required>
                                        @foreach (Modules\Pages\Models\Page::TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Show breadcrumb') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="has_breadcrumb" class="form-control select2" required>
                                        <option value="no">@lang('No')</option>
                                        <option value="yes">@lang('Yes')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Color scheme') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="dark_mode" class="form-control select2" required>
                                        <option value="0" @selected(setting('frontend_color_scheme') !== 'dark')>@lang('Light')</option>
                                        <option value="1" @selected(setting('frontend_color_scheme') === 'dark')>@lang('Dark')</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="card">
                    <div class="card-header flex-column align-items-start">
                        <div class="w-100 d-flex align-items-start justify-content-between">
                            <h4>@lang('Search engine listing preview')</h4>
                            <a href="javascript:void(0)"
                                class="btn toggle-page-seo-settings pr-0 text-danger">@lang('Edit website SEO')</a>
                        </div>
                        <div class="mt-3">
                            <p class="mb-0 blog-seo-preview-title-desc">@lang('Add a title and description to see how this page might appear in a search engine listing.')</p>
                            <div class="blog-seo-preview-title-desc-value d-none">
                                <strong class="blog-seo-preview-title"></strong>
                                <p class="mb-0 lh-sm blog-seo-preview-url"></p>
                                <p class="mb-0 blog-seo-preview-desc"></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body blog-seo-edit display-none">

                        <div class="form-group">
                            <label>@lang('Title') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" class="form-control" name="page_title" placeholder="@lang('e.g. Blog about your latest products or deals')"
                                data-max-size="70">
                            <div class="mt-1 text-muted form-help float-right">
                                <span class="page_title_length">0</span>
                                <span> @lang('of 70 characters used')</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Description') <span class="text-muted">(@lang('Required'))</span></label>
                            <textarea class="form-control" name="page_description" data-max-size="320"></textarea>
                            <div class="mt-1 text-muted form-help float-right">
                                <span class="page_description_length">0</span>
                                <span> @lang('of 320 characters used')</span>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label>@lang('URL and handle') <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text">{{ url('/pages') }}/</span>
                                </div>
                                <input type="text" class="form-control" name="slug">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card card-form page-section-wrapper">
                    <div class="card-header">
                        <h4>@lang('Sections')</h4>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm" id="page-section-table">
                            <tbody class="ui-sortable">

                            </tbody>
                        </table>
                        <button type="button" class="btn btn-block btn-primary" data-toggle="modal"
                            data-target="#add-page-section-modal">@lang('Add Section')</button>
                    </div>
                </div>

                <div class="card card-form page-content-wrapper display-none">
                    <div class="card-header">
                        <h4>@lang('Content')</h4>
                    </div>

                    <div class="card-body">
                        <textarea class="form-control" id="page-content"></textarea>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-default mr-3">@lang('Cancel')</a>
            <div class="btn-group dropleft">
                <button type="button" class="btn btn-primary" id="btn-create-page">@lang('Save')</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                    data-toggle="dropdown">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0)" id="btn-create-page-exit">@lang('Save and Exit')</a>
                    <a class="dropdown-item" href="javascript:void(0)"
                        id="btn-create-page-preview">@lang('Save and Preview')</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('modals')
    @include('pages::admin.modals.add-section')
    @include('pages::admin.modals.edit-section-details')
    @include('pages::admin.modals.edit-hero-section-details')
    @include('pages::admin.modals.edit-cta-section-details')
    @include('pages::admin.modals.edit-text-media-section-details')
    @include('pages::admin.modals.sections.hero')
    @include('pages::admin.modals.sections.about-us')
    @include('pages::admin.modals.sections.statistics')
    @include('pages::admin.modals.sections.testimonial')
    @include('pages::admin.modals.sections.recent-works')
    @include('pages::admin.modals.sections.boxed')
    @include('pages::admin.modals.sections.boxed-left-icon')
    @include('pages::admin.modals.sections.team')
    @include('pages::admin.modals.sections.client')
    @include('pages::admin.modals.sections.faq')
    @include('pages::admin.modals.sections.blog')
    @include('pages::admin.modals.sections.cta')
    @include('pages::admin.modals.sections.text-media')
    @include('photos::modals.select-image')
@endsection
