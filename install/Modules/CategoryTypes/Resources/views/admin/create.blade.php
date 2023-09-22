@extends('categorytypes::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.category-types.index') }}"> @lang('Category types')</a>
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
                
                <form id="admin-category-types-create-form" method="post">
                    <div class="card-body">
                        <div class="row">

                            

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Type') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="type" class="form-control select2" required>
                                        <option value="">@lang('Select type')</option>
                                        @foreach(Modules\CategoryTypes\Models\CategoryType::TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
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
                                <div class="form-group mb-0">
                                    <label for="description">@lang('Description') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <textarea name="description"
                                        class="form-control"></textarea>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.category-types.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>

            </div>
        </div>
    </div>
    

@endsection