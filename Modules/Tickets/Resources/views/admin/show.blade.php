@extends('tickets::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/tinymce/js/tinymce.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.tickets.index') }}"> @lang('Tickets')</a>
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
                    @include('tickets::partials.ticket-heading')
                </div>
                
                <div class="card-body p-20">

                    @include('tickets::partials.convo-actions')
                    @include('tickets::partials.convo-reply-form')
                    @include('tickets::partials.convo-list', [
                        'removeMediaRoute' => 'admin.tickets.remove-media'
                    ])

                    <p class="text-center"><a href="{{ route('admin.tickets.index') }}" class="btn btn-default btn-lg"><i class="fas fa-arrow-left"></i> @lang('Back to list')</a></p>
                </div>

            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-xm-12">
            @include('tickets::partials.details')
        </div>
    </div>
    

@endsection

@section('modals')
    @include('tickets::partials.modals.edit-convo')
    @include('tickets::partials.modals.edit-subject', ['editSubjectRoute' => 'admin.tickets.update-subject'])
@endsection