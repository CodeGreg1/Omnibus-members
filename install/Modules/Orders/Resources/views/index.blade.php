@extends('orders::layouts.master')

@section('module-actions')
    <a href="{{ route('orders.create') }}" class="btn btn-primary mt-1">Create new orders</a>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('orders.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p>
                @lang('This view is loaded from module: :name', [
                    'name' => config('orders.name')
                ])
            </p>
        </div>
    </div>
@endsection
