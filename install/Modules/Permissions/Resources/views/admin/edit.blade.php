@extends('roles::layouts.master')

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
            <a href="{{ route('admin.permissions.index') }}"> @lang('Permissions')</a>
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
                
                <form id="permission-edit-form" method="post" data-action="{{ route('admin.permissions.update', $permission->id) }}">
                    @method('patch')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="type" 
                                        value="{{ $permission->name }}" 
                                        name="name"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="display_name">@lang('Display Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="type" 
                                        value="{{ $permission->display_name }}" 
                                        name="display_name"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group mb-0">
                                    <label for="description">@lang('Description') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <input type="type" 
                                        value="{{ $permission->description }}"
                                        name="description"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
