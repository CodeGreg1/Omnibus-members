@extends('services::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/lineicons/css/LineIcons.2.0.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/tinymce/js/tinymce.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.services.index') }}"> @lang('Services')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    
    <div class="row">
        <div class="col-lg-6 col-md-6 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                
                <form id="admin-services-edit-form" method="post" data-action="{{ route('admin.services.update', $services->id) }}">
                    @method('patch')
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="icon">@lang('Icon') <span class="text-muted">(@lang('Required'))</span></label>

                                    <select name="icon" 
                                        class="form-control services-icon-select2"
                                        required>
                                        <option value="">@lang('Select Icon')</option>
                                        @foreach(array_merge(lineicons_all(), fontawesome_all()) as $icon)
                                            <option value="{{ $icon }}" {{ $services->icon == $icon ? 'selected' : '' }}>{{ $icon }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" 
                                        value="{{ $services->title }}"
                                        name="title"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="content">@lang('Content') <span class="text-muted">(@lang('Required'))</span></label>
                                    <textarea name="content"
                                        id="content"
                                        class="form-control admin-services-tinymce-default">{{ $services->content }}</textarea>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Visibility') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="visibility" class="form-control select2" required>
                                        <option value="">@lang('Select visibility')</option>
                                        @foreach(Modules\Services\Support\ServiceStatus::lists() as $key => $priority)
                                            <option value="{{ $key }}" {{ $services->visibility == $key ? 'selected' : '' }}>{{ $priority['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
