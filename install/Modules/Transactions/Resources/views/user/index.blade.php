@extends('transactions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> Dashboard</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('transactions.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div id="user-transactions-datatable"></div>
@endsection
