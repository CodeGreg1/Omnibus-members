@extends('blogs::layouts.master')

@section('module-styles')

@endsection

@section('module-scripts')
    <script src="{{ url('plugins/tinymce/js/tinymce.min.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.blogs.index') }}"> @lang('Blogs')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! $pageTitle !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    <form id="blog-create-form" method="post" class="admin-blog-form">
        <div class="row" id="package-form">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Title') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" class="form-control" name="title" placeholder="@lang('e.g. Blog about your latest products or deals')">
                        </div>
                        <div class="form-group mb-0">
                            <label>@lang('Description') <span class="text-muted">(@lang('Required'))</span></label>
                            <textarea class="form-control" name="description"></textarea>
                            <small class="form-text text-muted form-help">
                                @lang('Add a summary of the post to appear on your home page or blog.')
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header flex-column align-items-start">
                        <div class="w-100 d-flex align-items-start justify-content-between">
                            <h4>@lang('Search engine listing preview')</h4>
                            <a href="javascript:void(0)" class="btn toggle-blog-seo-settings pr-0 text-danger">@lang('Edit website SEO')</a>
                        </div>
                        <div class="mt-3">
                            <p class="mb-0 blog-seo-preview-title-desc">@lang('Add a title and description to see how this Blog post might appear in a search engine listing.')</p>
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
                            <input type="text" class="form-control" name="page_title" placeholder="@lang('e.g. Blog about your latest products or deals')" data-max-size="70">
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
                                <span class="input-group-text">{{ route('blogs.index') }}/</span>
                                </div>
                                <input type="text" class="form-control" name="slug">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label>@lang('Content') <span class="text-muted">(@lang('Required'))</span></label>
                            <textarea class="form-control" id="blog-content" name="content"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Thumbnail')</h4>
                        <small class="text-muted">(@lang('Required'))</small>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-1">
                            <div data-image-gallery="1100" name="media_id"></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Organizations')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Category') <span class="text-muted">(@lang('Required'))</span></label>
                            <select class="form-control select2" name="category_id" id="">
                                <option value="" selected>@lang('Select category')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="control-label mb-2">@lang('Add tag')</div>
                            <div class="dropdown" id="tagSearchDropdown">
                                <input type="text" class="dropdown-toggle tagsinput-search" id="tagSearch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="dropdown-menu">
                                    <div class="dropdown-title">@lang('Available tags')</div>
                                </div>
                            </div>
                            <small class="form-text text-muted form-help">
                                @lang('To add new tag input and press enter.')
                            </small>
                            <label class="mt-4">@lang('Tags') <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="tag-list form-control no-tag-list" name="tags">
                                <div class="no-tags">@lang('No tags added')</div>
                            </div>
                        </div>
                        <div class="form-group mb-1">
                            <label>@lang('Status') <span class="text-muted">(@lang('Required'))</span></label>
                            <select class="form-control select2" name="status" id="">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-end">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-cancel mr-2 btn-lg">@lang('Cancel')</a>
            <button class="btn btn-primary" type="button" id="btn-create-blog">@lang('Create blog post')</button>
        </div>
    </form>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
