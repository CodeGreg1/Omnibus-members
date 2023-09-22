@extends('categories::layouts.master')

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
            <a href="{{ route('admin.categories.index') }}"> @lang('Categories')</a>
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
                                <td>{{ $categories->id }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Type')</th>
                                <td>{{ $categories->category_type->type }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Parent')</th>
                                <td>{{ $categories->parent ? $categories->parent->name : '' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Name')</th>
                                <td>{{ $categories->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Description')</th>
                                <td>{{ $categories->description }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Color')</th>
                                <td><span class="badge" style="background-color: {{ $categories->color }}; padding: unset; height: 25px; width: 25px;">&nbsp;&nbsp;&nbsp;&nbsp;<span></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-center"><a href="{{ route('admin.categories.index') }}" class="btn btn-default btn-lg"><i class="fas fa-arrow-left"></i> @lang('Back to list')</a></p>
                </div>

            </div>
        </div>
    </div>
    

@endsection
