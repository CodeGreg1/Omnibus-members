@extends('languages::layouts.master')

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
            <a href="{{ route('admin.languages.index') }}"> @lang('Languages')</a>
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
                
                <form id="languages-edit-form" method="post" data-action="{{ route('admin.languages.update', $languages->id) }}">
                    @method('patch')
                    <div class="card-body">
                        <div class="row">
                            
                            
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" 
                                        value="{{ $languages->title }}"
                                        name="title"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="code">@lang('Code') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" 
                                        value="{{ $languages->code }}"
                                        name="code"
                                        class="form-control" required>
                                    <small class="form-text text-muted form-help">https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes</small>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Direction') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="direction" class="form-control select2">
                                        <option value="">Select direction</option>
                                        @foreach(Modules\Languages\Models\language::DIRECTION_SELECT as $key => $label)
                                            <option value="{{ $key }}" {{ $languages->direction == $label ? 'selected' : '' }}>{{ __($label) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="flag_id">@lang('Flag') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="flag_id" class="form-control select2" required>
                                        <option value="">Select flag</option>
                                        @foreach($flags as $id => $entry)
                                            <option value="{{ $id }}" {{ $languages->flag_id == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">

                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="hidden" name="active" value="0">
                                        <input type="checkbox" 
                                            name="active"
                                            class="custom-control-input" 
                                            id="active"
                                            value="1"
                                            {{ $languages->active == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="active">@lang('Active') <span class="text-muted">(@lang('Optional'))</span></label>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('admin.languages.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
