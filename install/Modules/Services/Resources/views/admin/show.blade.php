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
                
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">@lang('Icon')</th>
                                <td><span class="{{ $services->icon }}"></span></td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Title')</th>
                                <td>{{ $services->title }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Content')</th>
                                <td>{!! $services->content !!}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Visibility')</th>
                                <td><span class="badge badge-{{ Modules\FAQ\Support\FAQStatus::color($services->visibility) }}">{{ Modules\Services\Support\ServiceStatus::name($services->visibility) }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-center"><a href="{{ route('admin.services.index') }}" class="btn btn-default btn-lg"><i class="fas fa-arrow-left"></i> @lang('Back to list')</a></p>
                </div>

            </div>
        </div>
    </div>
    

@endsection
