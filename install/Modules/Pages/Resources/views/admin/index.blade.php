@extends('pages::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('module-actions')
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary mt-1">@lang('Create new page')</a>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! $pageTitle !!}</a>
        </li>
    </ol>
@endsection

@section('scripts')
    <script>
        const page_types = @json($types->map(function($label, $key) {
            return [
                'label' => $label,
                'value' => $key
            ];
        })->values());

        app.adminPageDatatable.table.loadFilters('type', page_types);
    </script>
@endsection

@section('content')
    @include('partials.messages')
    <div id="admin-pages-datatable"></div>
@endsection

@section('modals')
    @include('pages::admin.modals.duplicate-page')
@endsection
