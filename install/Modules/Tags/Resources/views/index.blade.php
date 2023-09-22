@extends('tags::layouts.master')

@section('module-actions')
    <a href="{{ route('tags.create') }}" class="btn btn-primary mt-1">Create new tags</a>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> Dashboard</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! config('tags.name') !!}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p>
                This view is loaded from module: {!! config('tags.name') !!}
            </p>
        </div>
    </div>
@endsection
