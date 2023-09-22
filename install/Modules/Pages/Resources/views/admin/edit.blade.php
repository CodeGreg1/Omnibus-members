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

@section('scripts')
    <script>
        app.adminPage.sections = @json($page->sections);
    </script>
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
            <a href="javascript:void(0)"> {!! $pageTitle !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <form class="admin-page-form" id="admin-page-update-form" method="post"
        data-route="{{ route('admin.pages.update', $page) }}">
        <div class="row">
            <div class="col-12">
                <input type="hidden" name="id" value="{{ $page->id }}">
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
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ $page->name }}" {{ $default && !$page->parent_id ? 'readonly' : '' }}>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description') <span
                                            class="text-muted">(@lang('Optional'))</span></label>
                                    <textarea name="description" class="form-control">{{ $page->description }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Type') <span class="text-muted">(@lang('Required'))</span></label>

                                    <select class="form-control" disabled name="type">
                                        @foreach (Modules\Pages\Models\Page::TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}" @selected($key === $page->type)>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Show breadcrumb') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="has_breadcrumb" class="form-control select2" required>
                                        <option value="no" @selected(!$page->has_breadcrumb)>@lang('No')</option>
                                        <option value="yes" @selected($page->has_breadcrumb)>@lang('Yes')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Color scheme') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="dark_mode" class="form-control select2" required>
                                        <option value="0" @selected(!$page->dark_mode)>@lang('Light')</option>
                                        <option value="1" @selected($page->dark_mode)>@lang('Dark')</option>
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
                        </div>
                        <div class="mt-3">
                            <p
                                class="mb-0 blog-seo-preview-title-desc {{ !$page->page_title && !$page->page_description ? '' : 'd-none' }}">
                                @lang('Add a title and description to see how this Blog post might appear in a search engine listing.')</p>
                            <div
                                class="blog-seo-preview-title-desc-value {{ !$page->page_title && !$page->page_description ? 'd-none' : '' }}">
                                <strong class="blog-seo-preview-title">{{ $page->page_title }}</strong>
                                @if ($page->slug === 'home')
                                    <p class="mb-0 lh-sm blog-seo-preview-url">{{ url('') }}</p>
                                @else
                                    <p class="mb-0 lh-sm blog-seo-preview-url">
                                        {{ $page->slug ? route('pages.show', $page->slug) : '' }}</p>
                                @endif
                                <p class="mb-0 blog-seo-preview-desc">{{ $page->page_description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            @php
                                $titlePlaceholder = \Illuminate\Support\Str::substr($page->name, 0, 70);
                            @endphp
                            <label>@lang('Title') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" class="form-control" name="page_title" data-max-size="70"
                                value="{{ $page->page_title }}" placeholder="{{ $titlePlaceholder }}">
                            <div class="mt-1 text-muted form-help float-right">
                                <span
                                    class="page_title_length">{{ \Illuminate\Support\Str::length($page->page_title) }}</span>
                                <span> @lang('of 70 characters used')</span>
                            </div>
                        </div>

                        <div class="form-group {{ $page->slug === 'home' ? 'mb-0' : '' }}">
                            @php
                                $descPlaceholder = \Illuminate\Support\Str::substr($page->description, 0, 320);
                            @endphp
                            <label>@lang('Description') <span class="text-muted">(@lang('Required'))</span></label>
                            <textarea class="form-control" name="page_description" data-max-size="320" placeholder="{{ $descPlaceholder }}">{{ $page->page_description }}</textarea>
                            <div class="mt-1 text-muted form-help float-right">
                                <span
                                    class="page_description_length">{{ \Illuminate\Support\Str::length($page->page_description) }}</span>
                                <span> @lang('of 320 characters used')</span>
                            </div>
                        </div>

                        @if ($page->slug !== 'home')
                            <div class="form-group mb-0">
                                @php
                                    $slugPlaceholder = \Illuminate\Support\Str::slug(\Illuminate\Support\Str::substr($page->name, 0, 70));
                                @endphp
                                <label>@lang('URL and handle') <span class="text-muted">(@lang('Required'))</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ url('/pages') }}/</span>
                                    </div>
                                    <input type="text" class="form-control" name="slug" value="{{ $page->slug }}"
                                        placeholder="{{ $slugPlaceholder }}" {{ $default ? 'readonly' : '' }}>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($page->type === 'section')
                    <div class="card card-form">
                        <div class="card-header">
                            <h4>@lang('Sections')</h4>
                        </div>

                        <div class="card-body">
                            <table class="table table-sm" id="page-section-table">
                                <tbody class="ui-sortable">
                                    @foreach ($page->sections as $section)
                                        <tr role="row" class="page-section-table-row" data-id="{{ $section->id }}">
                                            <td class="page-section-table-row-drag-handle">
                                                <span>
                                                    <i class="fas fa-arrows-alt drag-handle"></i>
                                                </span>
                                            </td>
                                            <td class="page-section-table-row-details">
                                                <div class="d-flex flex-column page-section-name">
                                                    <strong>{{ $section->name }}</strong>
                                                </div>
                                            </td>
                                            <td class="page-section-table-row-remove-handle">
                                                <div class="btn-group dropleft">
                                                    <button class="btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $section->id }}"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropleft">
                                                        <a class="dropdown-item has-icon btn-edit-page-section-details"
                                                            href="javascript:void(0)">
                                                            @lang('Edit section details')
                                                        </a>
                                                        @if ($section->has_data)
                                                            <a class="dropdown-item has-icon btn-edit-page-section-data"
                                                                href="javascript:void(0)">
                                                                @lang('Edit section data')
                                                            </a>
                                                        @endif
                                                        <a class="dropdown-item has-icon btn-remove-page-section"
                                                            href="javascript:void(0)">
                                                            @lang('Remove section')
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-block btn-primary" data-toggle="modal"
                                data-target="#add-page-section-modal">@lang('Add Section')</button>
                        </div>
                    </div>
                @endif

                @if ($page->type === 'wysiwyg')
                    <div class="card card-form">
                        <div class="card-header">
                            <h4>@lang('Content')</h4>
                        </div>

                        <div class="card-body">
                            <textarea class="form-control" id="page-content">{{ $page->content ? $page->content->body : '' }}</textarea>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-default mr-3">@lang('Cancel')</a>
            <div class="btn-group dropleft admin-page-actions {{ $page->type === 'wysiwyg' ? 'd-none' : '' }}">
                <button type="button" class="btn btn-primary" id="btn-update-page"
                    data-id="{{ $page->id }}">@lang('Save')</button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                    data-toggle="dropdown">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0)" id="btn-update-page-exit">@lang('Save and Exit')</a>
                    <a class="dropdown-item" href="javascript:void(0)"
                        id="btn-update-page-preview">@lang('Save and Preview')</a>
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
