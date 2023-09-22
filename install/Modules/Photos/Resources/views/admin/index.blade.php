@extends('photos::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-actions')
    @if ($folders->count())
        <a href="{{ route('admin.photos.gallery') }}" class="btn btn-icon icon-left btn-light mr-1"><i class="fas fa-list"></i> @lang('List')</a>
    @endif
    <a href="javascript:void(0)" class="btn btn-icon icon-left btn-light mr-1" data-toggle="modal" data-target="#admin-upload-photo-modal"><i class="fas fa-upload"></i> @lang('Upload')</a>
    <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#photo-new-folder-modal">@lang('Create new folder')</a>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Photos')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div class="row">
        @forelse ($folders as $folder)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin.photos.gallery', [
                            'folder' => $folder->id
                        ]) }}">
                            <h4>
                                <i class="fas fa-folder mr-1" style="font-size: 18px;"></i>
                                {{ $folder->name }}
                            </h4>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="gallery gallery-fw" data-item-height="100">
                            @if ($folder->total_images)
                                <a href="{{ route('admin.photos.gallery', [
                                    'folder' => $folder->id
                                ]) }}">
                                    @if ($folder->total_images === 1)
                                    <div class="gallery-item mb-0" data-image="{{ $folder->image }}" data-title="Image 1" href="{{ $folder->image }}" title="Image 1" style="height: 100px; background-image: url(&quot;{{ $folder->image }}&quot;);">
                                        </div>
                                    @else
                                        <div class="gallery-item gallery-more mb-0" data-image="{{ $folder->image }}" data-title="Image 3" href="{{ $folder->image }}" title="Image 3" style="height: 100px; background-image: url(&quot;{{ $folder->image }}&quot;);">
                                            @if ($folder->total_images > 1)
                                                <div style="line-height: 100px;">+{{ $folder->total_images - 1 }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </a>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center" style="line-height: 100px;">
                                    @lang('Folder is empty.')
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex">
                            <button type="button" class="btn btn-icon icon-left text-danger btn-photos-delete" data-route="{{ route('admin.photos.destroy', $folder->id) }}">
                                <i class="fas fa-trash"></i> @lang('Delete')
                            </button>
                            <button type="button" class="btn btn-icon icon-left text-success btn-photos-edit" data-id="{{ $folder->id }}" data-name="{{ $folder->name }}" data-description="{{ $folder->description }}">
                                <i class="fas fa-pen"></i> @lang('Edit')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-center py-5">
                    @lang('No folders yet')
                </div>
            </div>
        @endforelse
    </div>
@endsection

@section('modals')
    @include('photos::admin.modals.new-folder')
    @include('photos::admin.modals.edit-folder')
    @include('photos::admin.modals.upload-photo')
@endsection
