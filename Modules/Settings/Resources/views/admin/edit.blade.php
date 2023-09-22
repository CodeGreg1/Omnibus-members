@extends('settings::layouts.master')

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
            <a href="{{ route('admin.settings.index') }}"> @lang('Settings')</a>
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
                
                <form id="admin-settings-edit-form" method="post" data-action="{{ route('admin.settings.update', $settings->id) }}">
                    @method('patch')
                    <div class="card-body">
                        <div class="row">
                            
                            
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="key">@lang('Key') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" 
                                        value="{{ $settings->key }}"
                                        name="key"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="value">@lang('Value') <span class="text-muted">(@lang('Required'))</span></label>
                                    <textarea name="value"
                                        class="form-control" required>{{ $settings->value }}</textarea>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
