@extends('modules::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-actions')
    @can('admin.modules.create')
        <a href="{{ route('admin.modules.create') }}" class="btn btn-primary mt-1">@lang('Create new module')</a>
    @endcan
@endsection

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('modules.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')

    @if(env('APP_DEMO'))
        <div class="alert alert-info mt-3">
            NOTES FOR DEMO MODE:
            <p>1. Only users module model is shown the from BelongsTo & BelongsToMany relationship lists. To test these relationships, you can generate another module or use the users module model to test the relationships feature.</p>
            <p>2. All modules generated will automatically be deleted every six hours to clean our demo.</p>

            <br>

            FOR DEVELOPERS:
            <p>We recommend running <code>npm</code> manually to your local machine and push your compiled stylesheets & javascripts to your server.</p>
        </div>
    @endif

    <div class="alert alert-warning display-none" id="modules-message"></div>
    <div id="modules-datatable"></div>
@endsection
