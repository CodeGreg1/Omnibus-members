@extends('roles::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
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
                
                <form id="email-template-update-form" method="post" data-action="{{ route('admin.email-templates.update', $emailTemplate->id) }}">
                    @method('patch')
                    @csrf
                    <div class="card-body" x-on:load.window="app.emailTemplateCreate.tinyMCE()">
                        <div class="row">

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="type" 
                                        value="{{ $emailTemplate->name }}" 
                                        name="name"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="subject">@lang('Subject') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="type" 
                                        value="{{ $emailTemplate->subject }}" 
                                        name="subject"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <textarea id="edit-email-template-content">
                                    {!! $emailTemplate->content !!}
                                </textarea>
                            </div>

                            <div class="col-12 col-md-12 mt-3">@lang('Shortcodes'): {{ implode(", ", $shortcodes) }}</div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
