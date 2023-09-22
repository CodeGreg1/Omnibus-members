@extends('deposits::layouts.master')

@section('module-actions')
    <a href="{{ route('deposits.create') }}" class="btn btn-primary mt-1">Create new deposits</a>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> Dashboard</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('deposits.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p>
                This view is loaded from module: {!! config('deposits.name') !!}
            </p>
        </div>
    </div>
@endsection
