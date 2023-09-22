@extends('system::layouts.master')

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

@section('content')
    <div class="row align-items-center justify-content-center">
        <div class="col-12 col-md-8">
            <ul class="list-group">
                <li class="list-group-item py-3">
                    <div class="d-flex aling-items-cetner">
                        <i class="fas fa-check text-success mr-3" style="font-size: 16px;"></i>
                        <h6 class="mb-0">@lang('To perform migration kindly click the below button.')</h6>
                    </div>
                </li>
                <li class="list-group-item py-3">
                    <button class="btn btn-primary btn-lg btn-block" type="button" id="btn-migrate-database">
                        @lang('Migrate database')
                    </button>
                </li>
            </ul>
        </div>
    </div>
@endsection
