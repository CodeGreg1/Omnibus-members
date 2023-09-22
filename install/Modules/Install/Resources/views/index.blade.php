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
                    'install' => 1
                ])

                <div class="card card-primary">
                    <div class="card-header"><h4>{{ $pageTitle }}</h4></div>

                    <div class="card-body">
                        <p>
                            @lang('Easy setup wizard that will guide you the few steps installation process.')
                        </p>
                        <p>
                            @lang('If the installation process is completed, you may able to login and access the application.')
                        </p>

                        <a href="{{ route('install.requirements') }}" class="btn btn-primary float-right">@lang('Next') <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </section>
@endsection