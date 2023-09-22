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
    <form id="blog-update-form" method="patch" data-route="{{ route('admin.blogs.update', $blog) }}" class="admin-blog-form">
        <div class="row" id="package-form">
            <input type="hidden" name="id" value="{{ $blog->id }}">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input type="text" class="form-control" name="title" placeholder="e.g. Blog about your latest products or deals" value="{{ $blog->title }}">
                        </div>
                        <div class="form-group mb-0">
                            <label>@lang('Description')</label>
                            <textarea class="form-control" name="description">{{ $blog->description }}</textarea>
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
                        </div>
                        <div class="mt-3">
                            <p class="mb-0 blog-seo-preview-title-desc {{ (!$blog->page_title && !$blog->page_description) ? '' : 'd-none'}}">@lang('Add a title and description to see how this Blog post might appear in a search engine listing.')</p>
                            <div class="blog-seo-preview-title-desc-value {{ (!$blog->page_title && !$blog->page_description) ? 'd-none' : ''}}">
                                <strong class="blog-seo-preview-title">{{ $blog->page_title }}</strong>
                                <p class="mb-0 lh-sm text-primary blog-seo-preview-url">{{ $blog->slug ? route('blogs.show', $blog->slug) : '' }}</p>
                                <p class="mb-0 blog-seo-preview-desc">{{ $blog->page_description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            @php
                                $titlePlaceholder = \Illuminate\Support\Str::substr($blog->title, 0, 70);
                            @endphp
                            <label>@lang('Title') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" class="form-control" name="page_title" data-max-size="70" value="{{ $blog->page_title }}" placeholder="{{ $titlePlaceholder }}">
                            <div class="mt-1 text-muted form-help float-right">
                                <span class="page_title_length">0</span>
                                <span> @lang('of 70 characters used')</span>
                            </div>
                        </div>

                        <div class="form-group">
                            @php
                                $descPlaceholder = \Illuminate\Support\Str::substr($blog->description, 0, 320);
                            @endphp
                            <label>@lang('Description') <span class="text-muted">(@lang('Required'))</span></label>
                            <textarea class="form-control" name="page_description" data-max-size="320" placeholder="{{ $descPlaceholder }}">{{ $blog->page_description }}</textarea>
                            <div class="mt-1 text-muted form-help float-right">
                                <span class="page_description_length">{{ \Illuminate\Support\Str::length($blog->page_description) }}</span>
                                <span> @lang('of 320 characters used')</span>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            @php
                                $slugPlaceholder = \Illuminate\Support\Str::slug(\Illuminate\Support\Str::substr($blog->title, 0, 70));
                            @endphp
                            <label>@lang('URL and handle') <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text">{{ route('blogs.index') }}/</span>
                                </div>
                                <input type="text" class="form-control" name="slug" value="{{ $blog->slug }}" placeholder="{{ $slugPlaceholder }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label>@lang('Content')</label>
                            <textarea class="form-control" id="blog-content" name="content">{{ $blog->content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Thumbnail')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-1">
                            <div data-image-gallery="1101" name="media_id">
                                @if ($blog->thumbnail)
                                    <ul class="list-group">
                                        <li class="list-group-item" data-id="{{ $blog->thumbnail->id }}">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="gallery-photo-preview">
                                                        <img class="rounded" src="{{ $blog->thumbnail->preview_url }}" alt="{{ $blog->thumbnail->name }}" data-dz-thumbnail="" data-src="{{ $blog->thumbnail->original_url }}">
                                                    </div>
                                                </div>
                                                <div class="col overflow-hidden">
                                                    <h6 class="text-sm mb-1 image-name" data-name="{{ $blog->thumbnail->name }}">{{ $blog->thumbnail->name }}</h6>
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
                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Organizations')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Category')</label>
                            <select class="form-control select2" name="category_id" id="">
                                <option value="" selected>@lang('Select category')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($category->id==$blog->category_id)>{{ $category->name }}</option>
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
                            <div class="tag-list form-control {{ $blog->tags->count() ? '' : 'no-tag-list' }}" name="tags">
                                @if ($blog->tags->count())
                                    @foreach ($blog->tags as $tag)
                                        <div class="tag-item" data-id="{{ $tag->id }}" data-name="{{ $tag->name }}">
                                            <span class="tag-item-name">{{ $tag->name }}</span>
                                            <a href="javascript:void(0)" class="tag-item-remove-btn">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-tags">@lang('No tags added')</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-end">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-cancel mr-2 btn-lg">@lang('Cancel')</a>
            <button class="btn btn-primary d-none" type="button" id="btn-update-blog">@lang('Save changes')</button>
        </div>
    </form>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
