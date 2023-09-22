@extends('install::layouts.master')

@section('module-styles')

@endsection

@section('module-scripts')

@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <section class="section installation-section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2">
                <div class="login-brand">
                    <img src="{{ setting('colored_logo') }}" alt="logo" width="100" class="shadow-light">
                </div>

                @include('install::partials.wizards', [
                    'install' => 1,
                    'requirements' => 1
                ])

                <div class="card card-primary">
                    <div class="card-header"><h4>{{ $pageTitle }}</h4></div>

                    <div class="card-body">
                        <ul class="list-group mb-4">
                            @foreach ($requirements as $extension => $loaded)
                                <li class="list-group-item {{ ! $loaded ? 'list-group-item-danger' : '' }}">
                                    {{ $extension }}

                                    @if ($loaded)
                                        <span class="badge badge-success float-right"><i class="fa fa-check"></i></span>
                                    @else
                                        <span class="badge badge-danger float-right"><i class="fa fa-times"></i></span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('install.permissions') }}" class="btn btn-primary float-right">@lang('Next') <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </section>
@endsection