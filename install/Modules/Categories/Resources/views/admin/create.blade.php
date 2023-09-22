@extends('categories::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.categories.index') }}"> @lang('Categories')</a>
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
                
                <form id="admin-categories-create-form" method="post">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="category_type_id">@lang('Type') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="category_type_id" class="form-control select2" required>
                                        <option value="">@lang('Select type')</option>
                                        @foreach($categoryTypes as $categoryType)
                                            <option value="{{ $categoryType->id }}">{{ $categoryType->type }} -> {{ $categoryType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text"
                                        name="name"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <textarea name="description"
                                        class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group mb-0">
                                    <label>Color</label>
                                    <div class="input-group colorpickerinput">
                                        <input type="text" class="form-control" name="color">
                                        <div class="input-group-append">
                                            <div class="input-group-text" style="border-color: #e4e6fc;">
                                                <i class="fas fa-fill-drip"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>

            </div>
        </div>
    </div>
    

@endsection