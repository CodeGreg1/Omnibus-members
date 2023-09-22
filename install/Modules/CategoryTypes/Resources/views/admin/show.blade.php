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
                
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">@lang('Id')</th>
                                <td>{{ $categorytypes->id }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Type')</th>
                                <td>{{ $categorytypes->type }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Name')</th>
                                <td>{{ $categorytypes->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Description')</th>
                                <td>{{ $categorytypes->description }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-center"><a href="{{ route('admin.category-types.index') }}" class="btn btn-default btn-lg"><i class="fas fa-arrow-left"></i> @lang('Back to list')</a></p>
                </div>

            </div>
        </div>
    </div>
    

@endsection
