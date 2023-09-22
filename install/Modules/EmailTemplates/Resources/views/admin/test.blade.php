@extends('roles::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('plugins/tinymce/css/email-templates.css') }}">
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
            <a href="{{ route('admin.email-templates.index') }}"> @lang('Email templates')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    
    <div class="row">
        <div class="col-lg-8 col-md-8 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                
                <form id="email-template-send-form" method="post">
                    <input type="hidden" name="id" id="id">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="code">@lang('Template')</label>
                                    <select class="form-control" id="selectTemplate">
                                        <option value="">@lang('Select email template')</option>
                                        @foreach($emailTemplates as $emailTemplate)
                                            <option value="{{ $emailTemplate->id }}">{{ $emailTemplate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 display-none" id="sendToWrapper">
                                <div class="form-group">
                                    <label for="send_to">@lang('Send to') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="type"
                                        name="send_to"
                                        placeholder="@lang('Place email here')" 
                                        class="form-control">
                                </div>
                            </div>

                            
                            <div class="col-12 col-md-12" id="templateDefaultShortcodesTable">
                                
                            </div>

                            <div class="col-12 col-md-12" id="templateDefinedShortcodesTable">
                                
                            </div>

                            <div class="col-12 col-md-12" id="templateViewer">
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit" disabled>@lang('Send now')</button>
                        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
