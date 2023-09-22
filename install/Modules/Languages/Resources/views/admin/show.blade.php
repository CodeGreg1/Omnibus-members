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
                
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">@lang('Id')</th>
                                <td>{{ $languages->id }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Title')</th>
                                <td>{{ $languages->title }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Code</th>
                                <td>{{ $languages->code }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Direction')</th>
                                <td>{{ __($languages->direction) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Active')</th>
                                <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="col-active" {{ $languages->active == 1 ? 'checked' : '' }} disabled><label class="custom-control-label" for="col-active"></label></div></td>
                            </tr>


                        
                        </tbody>
                    </table>
                    <p class="text-center"><a href="{{ route('admin.languages.index') }}" class="btn btn-default btn-lg"><i class="fas fa-arrow-left"></i> @lang('Back to list')</a></p>
                </div>

            </div>
        </div>
    </div>
    

@endsection
